<?php

namespace App\EventSubscriber;

use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber extends AbstractController implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    /**
     * @param CalendarEvent $calendar
     */
    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $filters = $calendar->getFilters();
        $cours = array();
        if ($this->isGranted("ROLE_ELEVE")) {
            $cours = $this->getUser()->getGroupe()->getCours()->toArray();
        } elseif ($this->isGranted("ROLE_PROF")) {
            $cours = $this->getUser()->getCours()->toArray();
        }

        foreach ($cours as $c) {
            $p = $c->getProfesseur();
            $url = $this->generateUrl("cours_show", ["id" => $c->getId()]);
            $calendar->addEvent(new Event(
                $c->getMatiere()->getIntitule() . " par " . $p->getPrenom() . " " . $p->getNom(),
                $c->getDebut(),
                $c->getFin(),
                ["url" => $url]
            ));
        }
    }

}