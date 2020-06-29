<?php

namespace App\EventSubscriber;

use App\Entity\Examen;
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
            $cours = $this->getUser()->getGroupe()->getCours()
                ->filter(function ($cours) {
                    return $cours->getStatut();
                })
                ->toArray();
        } elseif ($this->isGranted("ROLE_PROF")) {
            $cours = $this->getUser()->getCours()
                ->filter(function ($cours) {
                    return $cours->getStatut();
                })
                ->toArray();
        }

        foreach ($cours as $c) {
            $p = $c->getProfesseur();
            $url = $this->generateUrl("cours_show", ["id" => $c->getId()]);
            if ($c instanceof Examen) {
                $color = "#dc3545";
            } else {
                $color = "#007bff";
            }
            $calendar->addEvent(new Event(
                $c->getMatiere()->getIntitule() . " par " . $p->getPrenom() . " " . $p->getNom(),
                $c->getDebut(),
                $c->getFin(),
                [
                    "url" => $url,
                    "color" => $color
                ]
            ));
        }
    }
}