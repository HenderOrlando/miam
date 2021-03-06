<ol class="timeline">
    <?php foreach ($timeline as $index => $entry): ?>
    <?php $story = $entry->getStory() ?>
    <li class="tentry<?php !$index && print ' first' ?> action_<?php echo $entry->getAction() ?> clearfix" id="tentry_<?php echo $entry->getId() ?>">

        <img class="gravatar" src="<?php echo Bundle\GravatarBundle\Api::getUrl(@$emails[$entry->getUser()->getUsername()], array('size' => 40)) ?>" width="40" height="40"/>
        <div class="title">
        <?php
        $storyUrl = $view->router->generate('story', array('id' => $story->getId()));
        echo strtr(
            '<strong class="tentry_action"><span class="tentry_user">{user}</span> ' . $entry->renderAction() . ' </strong><span class="date">{date}</span>',
            array(
                '{story}' => '',
                '{user}' => $entry->getUser()->getUsername(),
                '{date}' => '<span class="tentry_ago">à ' . $entry->getCreatedAt()->format('H:i') . '</span>',
                '{points}' => $entry->getPoints() ? $entry->getPoints() : '?'
        )) ?>
        </div>
        <?php $view->output('MiamBundle:Story:postit', array('story' => $story)) ?>
    </li>
    <?php endforeach ?>
</ol>
