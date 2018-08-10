<?php

namespace Drupal\signage_newrelic\EventSubscriber;


use Drupal\signage\Event\Input\InputEventInterface;
use Drupal\signage\Event\Payload\EventPayloadInterface;
use Drupal\webhooks\Event\ReceiveEvent;
use Drupal\webhooks\Event\WebhookEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class WebhookReceiveEventSubscriber implements EventSubscriberInterface {

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;

  /**
   * @var \Drupal\signage\Event\Payload\EventPayloadInterface
   */
  protected $payload;

  /**
   * @var \Drupal\signage\Event\Input\InputEventInterface
   */
  protected $inputEvent;

  /**
   * WebhookReceiveEventSubscriber constructor.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   */
  public function __construct(EventDispatcherInterface $event_dispatcher,
                              EventPayloadInterface $payload,
                              InputEventInterface $event) {
    $this->dispatcher = $event_dispatcher;
    $this->payload = $payload;
    $this->inputEvent = $event;
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    $events[WebhookEvents::RECEIVE][] = ['handleReceive', 10];
    return $events;
  }

  /**
   * @inheritDoc
   */
  public function handleReceive(ReceiveEvent $event) {

    $payload = $event->getWebhook()->getPayload();
    // Identify if this is a jira webook.
    //@todo patch WebhookService to pass the value of receive($name)
    // so we know that it is our webhook.

    $uri = \Drupal::request()->getRequestUri();
    if (strpos($uri, '/webhook/newrelic') === FALSE) {
      // Not a jira webhook.
      return;
    }

    // Just log events for now.
    \Drupal::logger('signage_newrelic')->info(
      'Content: %string',
      [
        '%string' => \Drupal::request()->getContent(),
      ]
    );



    //    // Build the source id that will be used by the term.
//    $status = strtolower($payload['build']['status']);
//    $source = 'newrelic.' . $status;
//
//    // Dispatch a signage input event.
//    $this->inputEvent->setSource($source);
//    $vals = $payload;
//    $this->payload->setValues($vals);
//    $this->inputEvent->setPayload($this->payload);
//    $this->dispatcher->dispatch('signage.input', $this->inputEvent);

  }

}
