<header class="hd">
    <div class="content">
        <h1 class="title_header">
            MIAM
        </h1>
        <div class="menu">
            <ul>
                <?php $activeMenu = $view->slots->get('active_menu') ?>
                <li><a <?php if('backlog' === $activeMenu): ?>class="active"<?php endif ?> href="<?php echo $view->router->generate('backlog') ?>">BACKLOG</a></li>
                <li><a <?php if('story_new' === $activeMenu): ?>class="active"<?php endif ?> href="<?php echo $view->router->generate('story_new') ?>">NOUVELLE STORY</a></li>
            </ul>
        </div>
    </div>
</header>