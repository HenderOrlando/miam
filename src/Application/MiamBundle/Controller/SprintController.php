<?php

namespace Application\MiamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller as Controller;
use Symfony\Components\HttpKernel\Exception\NotFoundHttpException;
use Application\MiamBundle\Entity\Sprint;
use Application\MiamBundle\Entity\Story;
use Application\MiamBundle\Renderer\SprintRenderer;
use Application\MiamBundle\Form\SprintForm;
use Symfony\Components\EventDispatcher\Event;

class SprintController extends Controller
{
    
    public function newAction()
    {
        $sprint = new Sprint();
        $sprint->setStartsAt(new \DateTime());
        $sprint->setEndsAt(new \DateTime());
        $form = new SprintForm('sprint', $sprint, $this->container->getValidatorService());

        if('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest()->get('sprint'));
            if($form->isValid()) {
                $this->getEntityManager()->persist($sprint);
                $this->getEntityManager()->flush();

                $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Sprint')->setCurrentSprint($sprint);
                
                $this->getEntityManager()->flush();
                
                $this->container->getSessionService()->setFlash('sprint_create', array('sprint' => $sprint->__toString()));

                return $this->redirect($this->generateUrl('homepage'));
            }
            
        }

        return $this->render('MiamBundle:Sprint:new', array(
            'form' => $form
        ));
    }

    public function pingAction($hash)
    {
        $sprint = $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Sprint')->findCurrent();
        $realHash = $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Story')->getCurrentSprintHash($sprint);

        if($realHash != $hash) {
            $sprint = $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Sprint')->findCurrentWithStories();
            $sections = $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Story')->findSprintStoriesIndexByProject($sprint);

            return $this->render('MiamBundle:Sprint:_current', array(
                'sections' => $sections,
                'sprint' => $sprint,
                'hash' => $realHash
            ));
        }

        return $this->createResponse('noop');
    }
    
    public function currentAction()
    {
        $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Project')->resort();
        $this->getEntityManager()->flush();
        return $this->render('MiamBundle:Sprint:current', array(
            'emails' => $this->container->getParameter('miam.user.emails')
        ));
    }

    public function scheduleAction()
    {
        $request = $this->getRequest();
        $id = $request->get('story_id');
        $story = $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Story')->find($id);
        if (!$story) {
            throw new NotFoundHttpException("Story '$id' not found");
        }
        $story->setPoints($request->get('points'));
        $status = $request->get('status');
        if(!Story::isValidStatus($status)) {
            throw new NotFoundHttpException($status.' is not a valid status');
        }
        $oldStatus = $story->getStatus();
        $story->setStatus($status);
        
        $sprint = $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Sprint')->findCurrentWithStories();
        if($story->isStatus(Story::STATUS_CREATED) && $oldStatus > Story::STATUS_CREATED) {
            $sprint->removeStory($story);
            $this->notify(new Event($story, 'miam.story.unschedule'));
        }
        elseif(!$story->isStatus(Story::STATUS_CREATED) && $oldStatus === Story::STATUS_CREATED) {
            $sprint->addStory($story);
            $this->notify(new Event($story, 'miam.story.schedule'));
        }
        else {
            $this->notify(new Event($story, 'miam.story.status'));
        }

        $ids = $this->getRequest()->get('story');
        $this->getEntityManager()->getRepository('Application\MiamBundle\Entity\Story')->sort($ids);

        $this->getEntityManager()->flush();
        
        return $this->forward('MiamBundle:Sprint:ping', array('hash' => null));
    }

    protected function notify(Event $event)
    {
        $this->container->getEventDispatcherService()->notify($event);
    }

    protected function getEntityManager()
    {
        return $this->container->getDoctrine_ORM_EntityManagerService();
    }

}
