<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Ilch\Date;
use Ilch\Mail;
use Ilch\Pagination;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Prefixes as PrefixMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Mappers\Rank as RankMapper;
use Modules\Forum\Mappers\TopicSubscription as TopicSubscriptionMapper;
use Modules\Forum\Mappers\Reports as ReportsMapper;
use Modules\Forum\Mappers\Remember as RememberMapper;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Forum\Mappers\TrackRead as TrackReadMapper;
use Modules\Forum\Models\ForumPost as ForumPostModel;
use Modules\Forum\Models\ForumTopic as ForumTopicModel;
use Ilch\Layout\Helper\LinkTag\Model as LinkTagModel;
use Modules\Forum\Models\Report as ReportModel;
use Modules\Admin\Models\Notification as NotificationModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Ilch\Accesses;
use Ilch\Validation;

class Showposts extends Frontend
{
    public function indexAction()
    {
        $postMapper = new PostMapper();
        $topicMapper = new TopicMapper();
        $forumMapper = new ForumMapper();
        $topicModel = new ForumTopicModel();
        $pagination = new Pagination();
        $rankMapper = new RankMapper();
        $reportsMapper = new ReportsMapper();
        $rememberMapper = new RememberMapper();

        $rowsPerPage = !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage');
        $pagination->setRowsPerPage($rowsPerPage);
        $pagination->setPage($this->getRequest()->getParam('page'));

        $topicId = $this->getRequest()->getParam('topicid');
        if (empty($topicId) || !is_numeric($topicId)) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Topic', 'errorText' => 'notFound']);
            return;
        }

        $forum = $forumMapper->getForumByTopicIdUser($topicId, $this->getUser());
        if ($forum === null) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Topic', 'errorText' => 'notFound']);
            return;
        }

        $cat = $forumMapper->getCatByParentId($forum->getParentId());
        $posts = $postMapper->getPostsByTopicId($topicId, $pagination, $this->getConfig()->get('forum_DESCPostorder'), ($this->getUser()) ? $this->getUser()->getId() : null);
        $topic = $topicMapper->getTopicById($topicId);

        $prefix = '';
        if ($forum->getPrefixes() != '' && $topic->getTopicPrefix()->getId() > 0) {
            $prefixIds = explode(',', $forum->getPrefixes());
            array_unshift($prefixIds, '');

            foreach ($prefixIds as $prefixId) {
                if ($topic->getTopicPrefix()->getId() == $prefixId) {
                    $prefix = '[' . $topic->getTopicPrefix()->getPrefix() . '] ';
                }
            }
        }

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle())
                ->add($forum->getTitle())
                ->add($prefix . $topic->getTopicTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum') . ' - ' . $forum->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()])
                ->add($prefix . $topic->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);

        $topicModel->setId($topicId);
        $topicModel->setVisits($topic->getVisits() + 1);
        $topicMapper->saveVisits($topicModel);

        $rememberedPostIds = [];
        $isSubscribed = false;
        if ($this->getUser()) {
            if (
                ($this->getConfig()->get('forum_DESCPostorder') && $this->getRequest()->getParam('page') == (1 || !$this->getRequest()->getParam('page')))
                || (!$this->getConfig()->get('forum_DESCPostorder') && ((($this->getRequest()->getParam('page')) ?? 1) == (ceil($pagination->getRows() / $rowsPerPage))))
            ) {
                // Mark topic as read if on the last page or on the first page with descending post order.
                $trackReadMapper = new TrackReadMapper();
                $trackReadMapper->markTopicAsRead($this->getUser()->getId(), $topicId, $forum->getId());
            }

            if ($this->getConfig()->get('forum_topicSubscription') == 1) {
                $topicSubscriptionMapper = new TopicSubscriptionMapper();

                $isSubscribed = $topicSubscriptionMapper->isSubscribedToTopic($topicId, $this->getUser()->getId());
            }

            $rememberedPosts = $rememberMapper->getRememberedPostsByTopicId($topicId, $this->getUser()->getId());
            foreach ($rememberedPosts as $rememberedPost) {
                $rememberedPostIds[] = $rememberedPost->getPostId();
            }
        }

        if (!empty($this->getConfig()->get('forum_filenameGroupappearanceCSS'))) {
            $linkTagModel = new LinkTagModel();
            $linkTagModel->setRel('stylesheet')
                ->setHref($this->getLayout()->getModuleUrl('static/css/groupappearance/' . $this->getConfig()->get('forum_filenameGroupappearanceCSS')));
            $this->getLayout()->add('linkTags', 'groupappearance', $linkTagModel);
        }

        $reportedPosts = $reportsMapper->getReports();
        $reportedPostsIds = [];
        foreach ($reportedPosts as $reportedPost) {
            $reportedPostsIds[] = $reportedPost->getPostId();
        }

        $this->getView()->set('post', $topic);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('posts', $posts);
        $this->getView()->set('forum', $forum);
        $this->getView()->set('prefix', $prefix);
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('rankMapper', $rankMapper);
        $this->getView()->set('postVoting', $this->getConfig()->get('forum_postVoting'));
        $this->getView()->set('topicSubscription', $this->getConfig()->get('forum_topicSubscription'));
        $this->getView()->set('isSubscribed', $isSubscribed);
        $this->getView()->set('reportingPosts', $this->getConfig()->get('forum_reportingPosts'));
        $this->getView()->set('reportedPostsIds', $reportedPostsIds);
        $this->getView()->set('rememberedPostIds', $rememberedPostIds);
    }

    public function deleteAction()
    {
        $postMapper = new PostMapper();
        $topicMapper = new TopicMapper();
        $forumMapper = new ForumMapper();

        $postId = (int)$this->getRequest()->getParam('id');
        $topicId = (int)$this->getRequest()->getParam('topicid');
        $forumId = (int)$this->getRequest()->getParam('forumid');
        $countPosts = $forumMapper->getCountPostsByTopicId($topicId);

        if ($this->getUser() && $this->getUser()->isAdmin() && $this->getRequest()->isSecure()) {
            $postMapper->deleteById($postId);
            if ($countPosts === 1) {
                $topicMapper->deleteById($topicId);
                $this->redirect(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forumId]);
            }

            $this->redirect(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);
        }

        $this->addMessage('noAccessDelete', 'danger');
        $this->redirect(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId, 'forumid' => $forumId]);
    }

    public function editAction()
    {
        $postMapper = new PostMapper();
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $prefixMapper = new PrefixMapper();

        $postId = $this->getRequest()->getParam('id');
        $topicId = $this->getRequest()->getParam('topicid');

        if (empty($topicId) || !is_numeric($topicId)) {
            $this->redirect()
                ->withMessage('topicNotFound', 'danger')
                ->to(['controller' => 'index', 'action' => 'index']);
        }
        if (empty($postId) || !is_numeric($postId)) {
            $this->redirect()
                ->withMessage('postNotFound', 'danger')
                ->to(['controller' => 'index', 'action' => 'index']);
        }

        $forum = $forumMapper->getForumByTopicId($topicId);

        if (!$forum) {
            $this->redirect()
                ->withMessage('forumNotFound', 'danger')
                ->to(['controller' => 'index', 'action' => 'index']);
        }

        $cat = $forumMapper->getCatByParentId($forum->getParentId());
        $post = $postMapper->getPostById($postId);

        if (!$post) {
            $this->redirect()
                ->withMessage('postNotFound', 'danger')
                ->to(['controller' => 'index', 'action' => 'index']);
        }

        $topic = $topicMapper->getTopicById($topicId);

        if (!$topic) {
            $this->redirect()
                ->withMessage('topicNotFound', 'danger')
                ->to(['controller' => 'index', 'action' => 'index']);
        }

        if ($this->getUser()) {
            if ($this->getUser()->isAdmin() || $this->getUser()->hasAccess('module_forum') || $this->getUser()->getId() == $post->getAutor()->getId()) {
                $this->getLayout()->getTitle()
                    ->add($this->getTranslator()->trans('forum'))
                    ->add($cat->getTitle())
                    ->add($forum->getTitle())
                    ->add($topic->getTopicTitle())
                    ->add($this->getTranslator()->trans('editPost'));
                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($cat->getTitle(), ['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()])
                    ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()])
                    ->add($topic->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId])
                    ->add($this->getTranslator()->trans('editPost'), ['controller' => 'newpost', 'action' => 'index', 'topicid' => $topicId]);

                $isFirstPost = $postMapper->isFirstPostOfTopic($topicId, $postId);

                if ($this->getRequest()->getPost('editPost')) {
                    $validationRules = [
                        'text' => 'required'
                    ];

                    if ($isFirstPost) {
                        $validationRules['topicTitle'] = 'required';
                    }

                    $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

                    if ($validation->isValid()) {
                        $postModel = new ForumPostModel();
                        $postModel->setId($postId)
                            ->setTopicId($topicId)
                            ->setText($this->getRequest()->getPost('text'));
                        $postMapper->save($postModel);

                        // Only allow updating of the topic title if it is the first post (normally the one, which started the topic)
                        // This ensures that only the autor or an admin can change the topic title
                        if ($isFirstPost) {
                            $topicMapper->update($topicId, 'topic_title', $this->getRequest()->getPost('topicTitle'));
                            $topicMapper->update($topicId, 'topic_prefix', $this->getRequest()->getPost('topicPrefix') ?? 0);
                        }

                        $this->redirect()
                            ->withMessage('saveSuccess')
                            ->to(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);
                    }

                    $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                    $this->redirect()
                        ->withInput()
                        ->withErrors($validation->getErrorBag())
                        ->to(['controller' => 'showposts', 'action' => 'edit', 'id' => $postId, 'topicid' => $topicId]);
                }

                $this->getView()->set('prefixes', $prefixMapper->getPrefixes());
                $this->getView()->set('forum', $forum);
                $this->getView()->set('topic', $topic);
                $this->getView()->set('post', $postMapper->getPostById($postId));
                $this->getView()->set('isFirstPost', $isFirstPost);
            } else {
                $this->redirect()
                    ->withMessage('noAccessForum', 'danger')
                    ->to(['controller' => 'index', 'action' => 'index']);
            }
        } else {
            $this->redirect()
                ->withMessage('noAccessForum', 'danger')
                ->to(['controller' => 'index', 'action' => 'index']);
        }
    }

    public function voteAction()
    {
        if ($this->getConfig()->get('forum_postVoting')) {
            $postMapper = new PostMapper();
            $postMapper->saveVotes($this->getRequest()->getParam('id'), $this->getUser()->getId());
        }

        $this->redirect(['action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]);
    }

    public function subscribeAction()
    {
        if ($this->getConfig()->get('forum_topicSubscription') == 1) {
            $topicSubscriptionMapper = new TopicSubscriptionMapper();

            $topicId = (int)$this->getRequest()->getParam('topicid');
            $isSubscribed = $topicSubscriptionMapper->isSubscribedToTopic($topicId, $this->getUser()->getId());

            if ($isSubscribed) {
                $topicSubscriptionMapper->deleteSubscription($topicId, $this->getUser()->getId());
            } else {
                $topicSubscriptionMapper->addSubscription($topicId, $this->getUser()->getId());
            }
        }

        $this->redirect(['action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]);
    }

    public function reportAction()
    {
        $topicId = (int)$this->getRequest()->getParam('topicid');
        $postId = (int)$this->getRequest()->getParam('postid');
        $reportsMapper = new ReportsMapper();

        $reportedPosts = $reportsMapper->getReports();
        $reportedPostsIds = [];
        foreach ($reportedPosts as $reportedPost) {
            $reportedPostsIds[] = $reportedPost->getPostId();
        }

        if (($this->getUser() && $this->getConfig()->get('forum_reportingPosts') == 1) && !\in_array($postId, $reportedPostsIds)) {
            if ($this->getRequest()->getPost()) {
                $validation = Validation::create($this->getRequest()->getPost(), [
                    'reason' => 'required|numeric|min:1|max:4'
                ]);

                if ($validation->isValid()) {
                    $notificationsMapper = new NotificationsMapper();
                    $notificationModel = new NotificationModel();
                    $reportsMapper = new ReportsMapper();
                    $reportModel = new ReportModel();

                    $notificationModel->setModule('forum');
                    $notificationModel->setMessage($this->getTranslator()->trans('reportedPostNotification'));
                    $notificationModel->setURL($this->getLayout()->getUrl(['module' => 'forum', 'controller' => 'reports', 'action' => 'index'], 'admin'));
                    $notificationModel->setType('forumReportedPost');

                    $notificationsMapper->addNotification($notificationModel);

                    $reportModel->setPostId($postId);
                    $reportModel->setUserId($this->getUser()->getId());
                    $reportModel->setReason($this->getRequest()->getPost('reason'));
                    $reportModel->setDetails($this->getRequest()->getPost('details'));
                    $reportsMapper->addReport($reportModel);

                    if ($this->getConfig()->get('forum_reportNotificationEMail') == 1) {
                        $groupIds = [1];
                        $users = [];
                        $groupMapper = new GroupMapper();
                        $userMapper = new UserMapper();

                        $forumAccessList = $groupMapper->getAccessAccessList('module', 'forum');
                        foreach ($forumAccessList['entries'] as $groupId => $accessLevel) {
                            // Access level 2 means right to edit in backend or in other words admin rights for
                            // that module.
                            if ($accessLevel == 2) {
                                $groupIds[] = $groupId;
                            }
                        }

                        foreach ($groupIds as $groupId) {
                            $users[] = $userMapper->getUserListByGroupId($groupId, 1);
                        }
                        $users = array_merge([], ...$users);

                        $receivedMail = [];
                        foreach ($users as $user) {
                            if (empty($user)) {
                                continue;
                            }

                            if (\in_array($user->getId(), $receivedMail)) {
                                continue;
                            }

                            $emailsMapper = new EmailsMapper();

                            $sitetitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
                            $username = $this->getLayout()->escape($user->getName());
                            $date = new Date();
                            $mailContent = $emailsMapper->getEmail('forum', 'post_reportedPost_mail', $this->getTranslator()->getLocale());
                            $layout = $_SESSION['layout'] ?? '';
                            if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/forum/layouts/mail/reportedPost.php')) {
                                $messageTemplate = file_get_contents(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/forum/layouts/mail/reportedPost.php');
                            } else {
                                $messageTemplate = file_get_contents(APPLICATION_PATH . '/modules/forum/layouts/mail/reportedPost.php');
                            }
                            $messageReplace = [
                                '{content}' => $this->getLayout()->purify($mailContent->getText()),
                                '{sitetitle}' => $sitetitle,
                                '{date}' => $date->format('l, d. F Y', true),
                                '{name}' => $username,
                                '{url}' => $this->getLayout()->getUrl(['module' => 'forum', 'controller' => 'reports', 'action' => 'index'], 'admin'),
                                '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                            ];
                            $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);
                            $mail = new Mail();
                            $mail->setFromName($sitetitle)
                                ->setFromEmail($this->getLayout()->escape($this->getConfig()->get('standardMail')))
                                ->setToName($username)
                                ->setToEmail($this->getLayout()->escape($user->getEmail()))
                                ->setSubject($this->getLayout()->escape($mailContent->getDesc()))
                                ->setMessage($message)
                                ->send();

                            $receivedMail[] = $user->getId();
                        }
                    }

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);
                }

                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withErrors($validation->getErrorBag())
                    ->to(['controller' => 'showposts', 'action' => 'report', 'topicid' => $topicId, 'postid' => $postId]);
            }
        } else {
            $this->redirect()
                ->withMessage('noAccessForum', 'danger')
                ->to(['controller' => 'index', 'action' => 'index']);
        }
    }
}
