<?php declare(strict_types=1);


namespace SwoftElaticsearch\Listener;

use App\Model\Entity\User;
use Swoft\Db\DbEvent;
use Swoft\Db\Eloquent\Model;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Class ModelSavedListener
 *
 * @since 2.0
 *
 * @Listener(DbEvent::MODEL_CREATED)
 */
class ModelSavedListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /** @var Model $modelStatic */
        $modelStatic = $event->getTarget();

//        $response = $modelStatic->makeIndex();
        $response = $modelStatic->makeAllIndex();
        var_dump($response);
    }
}
