<?php $view->extend('MiamBundle::layout') ?>

<div id="breadcrumb">
  <a id="back_backlog" href="<?php echo $view->router->generate('projects') ?>">Projets</a>
  &gt;
  <a href="<?php echo $view->router->generate('project', array('id' => $project->getId())) ?>">
    <?php echo $project->getName() ?>
  </a>
</div>

<h1>Projet <em><?php echo $project->getName() ?></em></h1>

<?php echo $form->renderFormTag($view->router->generate('project_edit', array('id' => $project->getId()))) ?>
    <table>
        <?php echo $form ?>
    </table>
    <input id="submit" type="submit" value="Valider" />
</form>