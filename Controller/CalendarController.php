<?php

namespace ADesigns\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use ADesigns\CalendarBundle\Event\CalendarEvent;

class CalendarController extends Controller
{
    /**
     * Dispatch a CalendarEvent and return a JSON Response of any events returned.
     * 
     * @param Request $request
     * @return Response
     */
    public function loadCalendarAction(Request $request)
    {
        $format = "Y-m-d H:i:s";
        $startDatetime = \DateTime::createFromFormat($format, date($format, strtotime($request->get('start'))));
        $endDatetime = \DateTime::createFromFormat($format, date($format, strtotime($request->get('end'))));
        
        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGURE, new CalendarEvent($startDatetime, $endDatetime, $request))->getEvents();
        
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $return_events = array();
        
        foreach($events as $event) {
            $return_events[] = $event->toArray();
        }
        
        $response->setContent(json_encode($return_events));
        
        return $response;
    }
}
