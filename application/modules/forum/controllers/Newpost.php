<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Mappers\TopicSubscription as TopicSubscriptionMapper;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Forum\Models\ForumPost as ForumPostModel;
use Modules\Forum\Config\Config as ForumConfig;
use Ilch\Validation;

class Newpost extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $postMapper = new PostMapper;

        $topicId = (int)$this->getRequest()->getParam('topicid');
        $forum = $forumMapper->getForumByTopicId($topicId);
        $cat = $forumMapper->getCatByParentId($forum->getParentId());
        $topic = $topicMapper->getPostById($topicId);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle())
                ->add($forum->getTitle())
                ->add($topic->getTopicTitle())
                ->add($this->getTranslator()->trans('newPost'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()])
                ->add($topic->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId])
                ->add($this->getTranslator()->trans('newPost'), ['controller' => 'newpost','action' => 'index', 'topicid' => $topicId]);

        $quotePostId = $this->getRequest()->getParam('quote');
        $postTextAsQuote = '';

        if ($quotePostId && is_numeric($quotePostId) && $quotePostId > 0) {
            $post = $postMapper->getPostById($quotePostId);

            // Check if the forum id of the post fits the id of the forum.
            // If that is not the case then don't even bother checking the rights
            // as the URL is invalid anyway.
            if ($post->getForumId() == $forum->getId()) {
                $userMapper = new UserMapper();

                $userId = null;
                if ($this->getUser()) {
                    $userId = $this->getUser()->getId();
                }
                $user = $userMapper->getUserById($userId);

                $readAccess = [3];
                if ($user) {
                    foreach ($user->getGroups() as $us) {
                        $readAccess[] = $us->getId();
                    }
                }

                if (is_in_array($readAccess, explode(',', $forum->getReadAccess())) || ($this->getUser() && $this->getUser()->isAdmin())) {
                    $postTextAsQuote = '[quote][b]'.$post->getAutor()->getName().' '.$this->getTranslator()->trans('wrote').':[/b]
                                       '.$post->getText().'[/quote]';
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

        if ($this->getRequest()->getPost('saveNewPost')) {
            $dateCreated = $postMapper->getDateOfLastPostByUserId($this->getUser()->getId());
            $isExcludedFromFloodProtection = is_in_array(array_keys($this->getUser()->getGroups()), explode(',', $this->getConfig()->get('forum_excludeFloodProtection')));

            if (!$isExcludedFromFloodProtection && ($dateCreated >= date('Y-m-d H:i:s', time()-$this->getConfig()->get('forum_floodInterval')))) {
                $this->addMessage('floodError', 'danger');
                $this->redirect()
                    ->withInput()
                    ->to(['controller' => 'newpost', 'action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]);
            } else {
                $validation = Validation::create($this->getRequest()->getPost(), [
                    'text' => 'required'
                ]);

                if ($validation->isValid()) {
                    $dateTime = new \Ilch\Date();

                    $postModel = new ForumPostModel;
                    $postModel->setTopicId($topicId)
                        ->setUserId($this->getUser()->getId())
                        ->setText($this->getRequest()->getPost('text'))
                        ->setForumId($forum->getId())
                        ->setDateCreated($dateTime);
                    $this->trigger(ForumConfig::EVENT_SAVEPOST_BEFORE, ['model' => $postModel]);
                    $postMapper->save($postModel);
                    $this->trigger(ForumConfig::EVENT_SAVEPOST_AFTER, ['model' => $postModel]);
                    $this->trigger(ForumConfig::EVENT_ADDPOST_AFTER, ['postModel' => $postModel, 'forum' => $forum, 'category' => $cat, 'topic' => $topic, 'request' => $this->getRequest()]);

                    $postsPerPage = (empty($this->getConfig()->get('forum_postsPerPage'))) ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage');
                    $page = floor(($forumMapper->getCountPostsByTopicId($topicId) - 1) / $postsPerPage) + 1;

                    // Notify subscribers
                    $topicSubscriptionMapper = new TopicSubscriptionMapper();
                    $subscribers = $topicSubscriptionMapper->getSubscriptionsForTopic($topicId);

                    foreach($subscribers as $subscriber) {
                        if ($subscriber->getUserId() == $this->getUser()->getId()) {
                            // Skip if post is from same user.
                            continue;
                        }

                        $date = new \Ilch\Date(date('Y-m-d H:i:s', strtotime('-5 minutes')));
                        if (strtotime($subscriber->getLastActivity()) >= strtotime($date->toDb(true))) {
                            // Skip if user was active within the last 5 minutes.
                            continue;
                        }

                        $date = new \Ilch\Date(date('Y-m-d H:i:s', strtotime('-15 minutes')));
                        if (strtotime($subscriber->getLastNotification()) >= strtotime($date->toDb(true))) {
                            // Skip if user received a notification within the last 15 minutes.
                            continue;
                        }

                        $emailsMapper = new EmailsMapper();

                        $sitetitle = $this->getConfig()->get('page_title');
                        $date = new \Ilch\Date();
                        $mailContent = $emailsMapper->getEmail('user', 'topic_subscription_mail', $this->getTranslator()->getLocale());
                        $layout = '';
                        if (isset($_SESSION['layout'])) {
                            $layout = $_SESSION['layout'];
                        }
                        if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/forum/layouts/mail/topicsubscription.php')) {
                            $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/forum/layouts/mail/topicsubscription.php');
                        } else {
                            $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/forum/layouts/mail/topicsubscription.php');
                        }
                        $messageReplace = [
                            '{content}' => $mailContent->getText(),
                            '{sitetitle}' => $sitetitle,
                            '{date}' => $date->format("l, d. F Y", true),
                            '{name}' => $subscriber->getUsername(),
                            '{url}' => $this->getLayout()->getURL(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId, 'page' => $page]),
                            '{topicTitle}' => $topic->getTopicTitle(),
                            '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                        ];
                        $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);
                        $mail = new \Ilch\Mail();
                        $mail->setFromName($this->getConfig()->get('page_title'))
                            ->setFromEmail($this->getConfig()->get('standardMail'))
                            ->setToName($subscriber->getUsername())
                            ->setToEmail($subscriber->getEmailAddress())
                            ->setSubject($mailContent->getDesc())
                            ->setMessage($message)
                            ->sent();

                        $topicSubscriptionMapper->updateLastNotification($topicId, $subscriber->getId());
                    }

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId, 'page' => $page]);
                }

                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['controller' => 'newpost', 'action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]);
            }
        }

        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topicPost', $topic);
        $this->getView()->set('postTextAsQuote', $postTextAsQuote);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('forum', $forum);
    }
}
