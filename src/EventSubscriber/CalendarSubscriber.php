<?php

namespace App\EventSubscriber;

use App\Repository\EleveRepository;
use App\Repository\ProfesseurRepository;
use App\Repository\UtilisateurRepository;
use App\Security\UserAuthenticator;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $professeurRepository;
    private $eleveRepository;

    public function __construct(LoggerInterface $logger, ProfesseurRepository $professeurRepository, EleveRepository $eleveRepository)
    {
        $this->logger = $logger;
        $this->professeurRepository = $professeurRepository;
        $this->eleveRepository = $eleveRepository;

    }

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
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
        $cours = array();
        switch ($filters["type"]) {
            case "ROLE_ELEVE":
                $cours = $this->getEleveCours($filters["id"]);
                break;
            case "ROLE_PROF":
                $cours = $this->getProfesseurCours($filters["id"]);
                break;
        }

        // You may want to make a custom query from your database to fill the calendar

        foreach ($cours as $c) {
            $p = $c->getProfesseur();
            $calendar->addEvent(new Event(
                $c->getMatiere()->getIntitule() . " par " . $p->getPrenom() . " " . $p->getNom(),
                $c->getDebut(),
                $c->getFin()
            ));
        }
    }

    /**
     * @param int $id
     * @return ArrayCollection
     */
    public function getEleveCours(int $id): array
    {
        $eleve = $this->eleveRepository->find($id);
        $groupe = $eleve->getGroupe();
        return $groupe->getCours()->toArray();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getProfesseurCours(int $id): array
    {
        $prof = $this->professeurRepository->find($id);
        return $prof->getCours()->toArray();
    }

}