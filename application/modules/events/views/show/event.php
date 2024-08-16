<?php

/** @var \Ilch\View $this */

/** @var \Modules\Events\Models\Events $event */
$event = $this->get('event');
/** @var \Modules\Events\Models\Entrants $eventEntrant */
$eventEntrant = $this->get('eventEntrants');
/** @var int $eventEntrantsCount */
$eventEntrantsCount = $this->get('eventEntrantsCount');

/** @var \Modules\User\Mappers\User $userMapper */
$userMapper = $this->get('userMapper');
/** @var \Modules\Events\Mappers\Currency $currencyMapper */
$currencyMapper = $this->get('currencyMapper');
/** @var \Modules\User\Models\User[] $userDetails */
$userDetails = $this->get('userDetails');

$user = null;
if (!empty($event)) {
    $start = new \Ilch\Date($event->getStart());
    $end = new \Ilch\Date($event->getEnd());
    $latLong = explode(',', $event->getLatLong());

    if ($event->getUserId()) {
        $user = $userDetails[$event->getUserId()] ?? $userMapper->getUserById($event->getUserId());
    }
}
?>

<?php include APPLICATION_PATH . '/modules/events/views/index/navi.php'; ?>

<?php if (!empty($event)) : ?>
    <h1>
        <?=$this->getTrans('event') ?>
        <?php if ($this->getUser() && ($event->getUserId() == $this->getUser()->getId() || $this->getUser()->hasAccess('module_events'))) : ?>
            <div class="float-end">
                <?=$this->getEditIcon(['controller' => 'index', 'action' => 'treat', 'id' => $event->getId()]) ?>
                <?=$this->getDeleteIcon(['controller' => 'index', 'action' => 'del', 'id' => $event->getId()]) ?>
            </div>
        <?php endif; ?>
    </h1>

    <div class="row">
        <div class="col-xl-12">
            <div class="event-head">
                <?php if ($this->escape($event->getImage()) != '') : ?>
                    <img src="<?=$this->getBaseUrl() . $this->escape($event->getImage()) ?>" class="headPic" alt="<?=$this->getTrans('headpic') ?>">
                <?php else : ?>
                    <img src="<?=$this->getModuleUrl('static/img/450x150.jpg') ?>" class="headPic" alt="<?=$this->getTrans('headpic') ?>">
                <?php endif; ?>
                <div class="datePic">
                    <div class="dateDayPic"><?=$start->format('d') ?></div>
                    <div class="dateMonthPic"><?=$this->getTrans($start->format('M')) ?></div>
                </div>
                <div class="titlePic"><?=$this->escape($event->getTitle()) ?></div>
            </div>
            <div class="naviPic">
                <?php if ($event->getUserId()) : ?>
                    <div class="naviGast">
                        <strong><?=$this->getTrans('by') ?></strong> <a href="<?=$this->getUrl('user/profil/index/user/' . $user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a>
                    </div>
                <?php endif; ?>
                <div class="naviButtons">
                    <?php if ($this->getUser() && $event->getStart() > new \Ilch\Date()) : ?>
                        <form method="POST" action="">
                            <?=$this->getTokenField() ?>
                            <input type="hidden" name="id" value="<?=$this->escape($event->getId()) ?>">
                            <?php if ($eventEntrant != '') : ?>
                                <?php if ($eventEntrant->getUserId() != $this->getUser()->getId()) : ?>
                                    <button type="submit" class="btn btn-sm btn-success" name="save" value="1">
                                        <?=$this->getTrans('join') ?>
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-warning" name="save" value="2">
                                        <?=$this->getTrans('maybe') ?>
                                    </button>
                                <?php else : ?>
                                    <?php if ($eventEntrant->getStatus() == 1) : ?>
                                        <button type="submit" class="btn btn-sm btn-warning" name="save" value="2">
                                            <?=$this->getTrans('maybe') ?>
                                        </button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-sm btn-success" name="save" value="1">
                                            <?=$this->getTrans('agree') ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-sm btn-danger" name="deleteUser" value="deleteUser">
                                    <?=$this->getTrans('decline') ?>
                                </button>
                            <?php else : ?>
                                <?php if ($eventEntrantsCount < $event->getUserLimit() || $event->getUserLimit() == 0) : ?>
                                    <button type="submit" class="btn btn-sm btn-success" name="save" value="1">
                                        <?=$this->getTrans('join') ?>
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-warning" name="save" value="2" >
                                        <?=$this->getTrans('maybe') ?>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>
                    <?php else : ?>
                        <a href="<?=$this->getUrl('user/login/index') ?>" class="btn btn-sm btn-primary">
                            <?=$this->getTrans('login') ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <br />
            <div class="eventBoxHead">
                <?php if ($event->getEnd() !== '0000-00-00 00:00:00') : ?>
                    <?php if (strtotime($event->getEnd()) - strtotime($event->getStart()) > 86400) : ?>
                        <?php $eventDate = $start->format('H:i') . ' - ' . $this->getTrans($end->format('l')) . $end->format(', d. ') . $this->getTrans($end->format('F')) . $end->format(' Y') . ' ' . $this->getTrans('at') . ' ' . $end->format('H:i'); ?>
                    <?php else : ?>
                        <?php $eventDate = $start->format('H:i') . ' - ' . $end->format('H:i'); ?>
                    <?php endif; ?>
                <?php else : ?>
                    <?php $eventDate = $start->format('H:i'); ?>
                <?php endif; ?>

                <i class="fa-regular fa-clock"></i> <?=$this->getTrans($start->format('l')) . $start->format(', d. ') . $this->getTrans($start->format('F')) . $start->format(' Y') ?> <?=$this->getTrans('at') ?> <?=$eventDate ?> <?=$this->getTrans('clock') ?>
            </div>
            <div class="eventBoxBottom">
                <?=$this->escape($event->getType()) ?>
            </div>
            <div class="eventBoxBottom">
                <?php $place = $this->escape($event->getPlace()); ?>
                <?php $place = explode(', ', $place, 2); ?>
                <div class="eventPlaceMarker">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <?php
                if ($this->get('event_google_maps_api_key') != '' && $event->getLatLong() != '') {
                    echo '<a id="showMap">' . $place[0] . '</a>';
                } else {
                    echo $place[0];
                }
                if (!empty($place[1])) {
                    echo '<br /><span class="eventAddress text-muted">' . $place[1] . '</span>';
                }
                ?>
                <?php if ($this->get('event_google_maps_api_key') != '' && $event->getLatLong() != '') : ?>
                    <div id="googleMap" style="display: none">
                        <div id="map-canvas" data-bs-toggle="modal" data-bs-target="#showBigGoogleMapsModal"></div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($event->getWebsite()) : ?>
                <div class="eventBoxBottom">
                    <i class="fa-solid fa-earth-americas"></i> <a href="<?=$userMapper->getHomepage($event->getWebsite()) ?>" target="_blank" rel="noopener"><?=$this->getTrans('website') ?></a>
                </div>
            <?php endif; ?>

            <?php if ($event->getPrice() != '' && $event->getCurrency() >= 1) : ?>
                <br />
                <div class="eventBoxHead">
                    <strong><?=$this->getTrans('price') ?></strong>
                </div>
                <div class="eventBoxContent">
                    <?php if ($event->getPriceArt() >= 1) {
                        if ($event->getPriceArt() == 1) {
                            echo $this->getTrans('ticket') . ' ';
                        } else {
                            echo $this->getTrans('entry') . ' ';
                        }
                    }

                    echo str_replace('.', ',', $event->getPrice()) . ' ';

                    $currency = $currencyMapper->getCurrencyById($event->getCurrency());
                    echo $currency[0]->getName();
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($event->getUserLimit() > 0) : ?>
                <div class="eventBoxBottom">
                    <i class="fa-solid fa-users"></i> <?=$eventEntrantsCount ?> / <?=$event->getUserLimit() ?>
                </div>
            <?php endif; ?>
            <br />
            <div class="eventBoxHead">
                <div style="width: 10%; float: left;">
                    <strong><?=$this->getTrans('entrant') ?></strong>
                </div>
                <div style="width: 45%; float: left;" class="text-center">
                    <?php $agree = 0;
                    $maybe = 0; ?>
                    <?php if ($eventEntrantsCount != '') : ?>
                        <?php
                        /** @var \Modules\Events\Models\Entrants $eventEntrantsUser */
                        foreach ($this->get('eventEntrantsUsers') as $eventEntrantsUser) : ?>
                            <?php if ($eventEntrantsUser->getStatus() == 1) : ?>
                                <?php $agree++; ?>
                            <?php elseif ($eventEntrantsUser->getStatus() == 2) : ?>
                                <?php $maybe++; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?=$agree ?> <?=$this->getTrans('agree') ?>
                </div>
                <div style="width: 45%; float: left;" class="text-center">
                    <?=$maybe ?> <?=$this->getTrans('maybe') ?>
                </div>
                <div style="clear: both;"></div>
            </div>
            <?php if (($this->getUser() && $this->getUser()->isAdmin()) || ($eventEntrantsCount != '' && is_in_array($this->get('showMembersAccess'), explode(',', $this->get('showMembersAccesses'))))) : ?>
                <div class="eventBoxBottom">
                    <div style="margin-left: 2px;">
                        <?php if ($eventEntrantsCount != '') : ?>
                            <?php
                            /** @var \Modules\Events\Models\Entrants $eventEntrantsUser */
                            foreach ($this->get('eventEntrantsUsers') as $eventEntrantsUser) : ?>
                                <?php $entrantsUser = $userDetails[$eventEntrantsUser->getUserId()]; ?>
                                <a href="<?=$this->getUrl('user/profil/index/user/' . $entrantsUser->getId()) ?>" target="_blank"><img class="events-thumbnail <?=($eventEntrantsUser->getStatus() == 1) ? 'events-thumbnail-agree' : 'events-thumbnail-maybe' ?>" src="<?=$this->getStaticUrl() . '../' . $this->escape($entrantsUser->getAvatar()) ?>" title="<?=$this->escape($entrantsUser->getName()) ?>" alt="<?=$this->getTrans('avatar') ?>"></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="more-entrants">
                        <a href="" data-bs-toggle="modal" data-bs-target="#entrantsModal"><?=$this->getTrans('showMore') ?></a>
                    </div>
                </div>
            <?php endif; ?>
            <br />
            <div class="eventBoxHead">
                <strong><?=$this->getTrans('description') ?></strong>
            </div>
            <div class="eventBoxContent">
                <?=$this->alwaysPurify($event->getText()) ?>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-xl-12">
            <?php if ($this->getUser() && (($eventEntrant != '' && $eventEntrant->getUserId() == $this->getUser()->getId()) || $event->getUserId() == $this->getUser()->getId())) : ?>
                <div class="row mb-3 eventCommentSubmit">
                    <form action="" method="POST">
                        <?=$this->getTokenField() ?>
                        <input type="hidden" name="id" value="<?= $this->escape($event->getId()) ?>">
                        <div style="margin-bottom: 10px; margin-top: 10px;">
                            <div class="col-xl-12">
                                <textarea class="eventTextarea"
                                          name="commentEvent"
                                          placeholder="<?=$this->getTrans('writeToEvent') ?>"
                                          required></textarea>
                            </div>
                        </div>
                        <div class="col-xl-12 eventSubmit">
                            <button type="submit" class="float-end btn btn-secondary btn-sm" name="saveEntry">
                                <?=$this->getTrans('write') ?>
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($this->get('eventComments') != '') : ?>
                <div class="eventBoxHead">
                    <strong><?=$this->getTrans('comments') ?></strong>
                </div>
                <?php
                /** @var \Modules\Comment\Models\Comment $eventComment */
                foreach ($this->get('eventComments') as $eventComment) : ?>
                    <?php $commentUser = (isset($userDetails[$eventComment->getUserId()])) ? $userDetails[$eventComment->getUserId()] : $userMapper->getUserById($eventComment->getUserId()); ?>
                    <?php $commentUser = (empty($commentUser)) ? $userMapper->getDummyUser() : $commentUser; ?>
                    <?php $commentDate = new \Ilch\Date($eventComment->getDateCreated()); ?>
                    <div class="eventBoxContent" id="<?=$eventComment->getId() ?>">
                        <?php if ($this->getUser()) : ?>
                            <?php if ($event->getUserId() == $this->getUser()->getId() || $commentUser->getId() == $this->getUser()->getId()) : ?>
                                <div class="float-end" style="height: 40px; top: 0;"><?=$this->getDeleteIcon(['action' => 'del', 'id' => $eventComment->getId(), 'eventid' => $this->getRequest()->getParam('id')]) ?></div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="float-start"><a href="<?=$this->getUrl('user/profil/index/user/' . $commentUser->getId()) ?>" target="_blank"><img class="avatar" src="<?=$this->getUrl() . '/' . $commentUser->getAvatar() ?>" alt="User Avatar"></a></div>
                        <div class="userEventInfo">
                            <a href="<?=$this->getUrl('user/profil/index/user/' . $commentUser->getId()) ?>" target="_blank"><?=$this->escape($commentUser->getName()) ?></a><br />
                            <span class="small"><?=$commentDate->format('Y.m.d H:i', true) ?></span>
                        </div>
                        <div class="commentEventText"><?=nl2br($this->escape($eventComment->getText())) ?></div>
                    </div>
                    <br />
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($eventEntrantsCount != '') : ?>
        <!-- Entrants Modal -->
        <div id="entrantsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?=$this->getTrans('entrant') ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?=$this->getTrans('close') ?>"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        /** @var \Modules\Events\Models\Entrants $eventEntrantsUser */
                        foreach ($this->get('eventEntrantsUsers') as $eventEntrantsUser) : ?>
                            <div class="entrants-user">
                                <?php $entrantsUser = $userDetails[$eventEntrantsUser->getUserId()]; ?>
                                <a href="<?=$this->getUrl('user/profil/index/user/' . $entrantsUser->getId()) ?>" class="entrants-user-link">
                                    <img class="events-thumbnail <?=($eventEntrantsUser->getStatus() == 1) ? 'events-thumbnail-agree' : 'events-thumbnail-maybe' ?>" src="<?=$this->getStaticUrl() . '../' . $this->escape($entrantsUser->getAvatar()) ?>" title="<?=$this->escape($entrantsUser->getName()) ?>" alt="<?=$this->getTrans('avatar') ?>">
                                    <?=$entrantsUser->getName() ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->get('event_google_maps_api_key') != '') : ?>
        <?php $place = $this->escape($event->getPlace()); ?>
        <?=$this->getDialog('showBigGoogleMapsModal', $place, '<div id="big-map-canvas"></div>') ?>

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?=$this->get('event_google_maps_api_key') ?>" async defer></script>
    <?php endif; ?>

    <script>
    // Textarea AutoResize
    $('textarea').on('keyup', function() {
        $(this).css('height', 'auto');
        $(this).height(this.scrollHeight);
    });

    <?php if ($this->get('event_google_maps_api_key') != '' && $event->getLatLong() != '') : ?>
        // Google Maps
        $(document).ready(function() {
            var mapCanvas = document.getElementById('map-canvas');
            var latLng = {lat: <?=$latLong[0] ?>, lng: <?=$latLong[1] ?>};
            var mapOptions = {
                zoom: 15,
                center: latLng,
                mapTypeId: google.maps.MapTypeId.<?=$this->get('event_google_maps_map_typ') ?>,
                disableDefaultUI: true,
                disableDoubleClickZoom: true,
                scrollwheel: false,
                draggable: false,
                clickableIcons: false
            };

            $("#showMap").click(function() {
                $("#googleMap").slideToggle("slow");
                var map = new google.maps.Map(mapCanvas, mapOptions);

                setTimeout(function() {
                    var marker = new google.maps.Marker({
                        position: latLng,
                        map: map,
                        draggable: false,
                        title: '<?=$this->escape($event->getTitle()) ?>',
                        animation: google.maps.Animation.DROP
                    });
                }, 600);
            });

            $('#showBigGoogleMapsModal').on('shown.bs.modal', function () {
                bigMap();
            });

            function bigMap() {
                var mapCanvas = document.getElementById('big-map-canvas');
                var mapOptions = {
                    zoom: <?=$this->get('event_google_maps_zoom') ?>,
                    center: latLng,
                    mapTypeId: google.maps.MapTypeId.<?=$this->get('event_google_maps_map_typ') ?>,
                    streetViewControl: false
                };

                var map = new google.maps.Map(mapCanvas, mapOptions);

                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    title: '<?=$this->escape($event->getTitle()) ?>',
                    animation: google.maps.Animation.DROP
                });

                <?php $place = $this->escape($event->getPlace()); ?>
                <?php $infoPlace = str_replace(', ', '<br />', $place); ?>
                <?php $infoRoutePlace = str_replace(' ', '+', $place); ?>
                var infoWindowContent = '<div class="poi-info-window"><div class="title"><?=$this->escape($event->getTitle()) ?></div><div class="address"><?=$infoPlace ?><br /><a href="https://maps.google.com?daddr=<?=$infoRoutePlace ?>" target="_blank" rel="noopener"><?=$this->getTrans('googleMapsPlanRoute') ?></a></div></div>';
                var infowindow = new google.maps.InfoWindow({
                    content: infoWindowContent
                });

                setTimeout(function() {
                    infowindow.open(map,marker);
                }, 700);

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });
            }
        });
    <?php endif; ?>
    </script>
<?php endif; ?>
