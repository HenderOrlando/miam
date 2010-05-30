<ol class="stories">
    <?php foreach ($stories as $story): ?>
        <li class="story" id="story_<?php echo $story->getId() ?>">
            <a class="status_<?php echo $story->renderStatus() ?>" href="<?php echo $view->router->generate('story', array('id' => $story->getId())) ?>"><?php echo $story->getName() ?></a>
            <span class="story_project" style="background:<?php echo $story->getProject()->getColor() ?>">
                <a href="<?php echo $view->router->generate('project', array('id' => $story->getProject()->getId())) ?>">
                    <?php echo $story->getProject()->getName() ?>
                </a>
            </span>
            <span class="story_points"><?php echo $story->getPoints() ? $story->getPoints() : '?' ?></span>
            <?php if ($story->isScheduled()): ?>
                <span class="unscheduleLink"><a class="js_confirm" title="Retirer cette story"  href="<?php echo $view->router->generate('sprint_unschedule', array('id' => $story->getId())) ?>">annuler</a></span>
            <?php endif; ?>
        </li>
    <?php endforeach ?>
</ol>
