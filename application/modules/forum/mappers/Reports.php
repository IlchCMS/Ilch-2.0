<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\Report as ReportModel;

class Reports extends \Ilch\Mapper
{
    /**
     * @param array $where
     * @return array|ReportModel[]
     */
    private function getBy($where = [])
    {
        $reportsArray = $this->db()->select('*')
            ->fields(['r.id', 'r.date', 'r.post_id', 'r.reason', 'r.details', 'r.user_id'])
            ->from(['r' => 'forum_reports'])
            ->join(['u' => 'users'], 'u.id = r.user_id', 'LEFT', ['u.name'])
            ->join(['p' => 'forum_posts'], 'p.id = r.post_id', 'LEFT', ['p.forum_id', 'p.topic_id'])
            ->where($where)
            ->execute()
            ->fetchRows();

        $reports = [];

        foreach ($reportsArray as $reportRow) {
            $reportModel = new ReportModel();
            $reportModel->setId($reportRow['id']);
            $reportModel->setDate($reportRow['date']);
            $reportModel->setForumId($reportRow['forum_id']);
            $reportModel->setTopicId($reportRow['topic_id']);
            $reportModel->setPostId($reportRow['post_id']);
            $reportModel->setReason($reportRow['reason']);
            $reportModel->setDetails($reportRow['details']);
            $reportModel->setUserId($reportRow['user_id']);
            $reportModel->setUsername($reportRow['name']);
            $reports[] = $reportModel;
        }

        return $reports;
    }

    /**
     * Get all reports.
     *
     * @return array|ReportModel[]
     */
    public function getReports()
    {
        return $this->getBy();
    }

    /**
     * Get report by id.
     *
     * @param $id
     * @return null|ReportModel
     */
    public function getReportById($id)
    {
        $report = $this->getBy(['r.id' => $id]);

        if (!empty($report)) {
            return reset($report);
        }

        return null;
    }

    /**
     * Add report.
     *
     * @param $report
     */
    public function addReport($report)
    {
        $date = new \Ilch\Date();

        $this->db()->insert('forum_reports')
            ->values([
                'date' => $date,
                'post_id' => $report->getPostId(),
                'reason' => $report->getReason(),
                'details' => $report->getDetails(),
                'user_id' => $report->getUserId()
            ])
            ->execute();
    }

    /**
     * Delete report by id.
     *
     * @param $id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function deleteReport($id)
    {
        return $this->db()->delete('forum_reports')
            ->where(['id' => $id])
            ->execute();
    }
}
