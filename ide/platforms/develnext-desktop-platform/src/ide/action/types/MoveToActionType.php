<?php
namespace ide\action\types;

use action\Animation;
use ide\action\AbstractActionType;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;
use php\xml\DomDocument;
use php\xml\DomElement;

class MoveToActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_CONTROL;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_ANIMATION;
    }

    function attributes()
    {
        return [
            'object' => 'object',
            'duration' => 'integer',
            'x' => 'float',
            'y' => 'float',
            'continue' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'wizard.object::Объект',
            'duration' => 'wizard.anim.duration::Продолжительность анимации (млсек, 1 сек = 1000 млсек)',
            'x' => 'wizard.x.position::Позиция X',
            'y' => 'wizard.y.position::Позиция Y',
            'continue' => 'wizard.no.wait.the.anim.end::Не ждать окончания анимации'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'duration' => ['def' => 1000],
            'x' => ['def' => 0],
            'y' => ['def' => 0],
        ];
    }

    function getTagName()
    {
        return "moveTo";
    }

    function getTitle(Action $action = null)
    {
        return "wizard.command.anim.move.to.point::Передвинуть к точке (анимация)";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "wizard.command.desc.anim.move.to.point::Анимация линейного перемещения к точке";
        }

        $object = $action->get('object');
        $duration = $action->get('duration');
        $x = $action->get('x');
        $y = $action->get('y');

        if ($action->continue) {
            return _("wizard.command.desc.param.anim.move.to.point::Перемещать объект {0} к точке (x: {1}, y: {2}) за {3} млсек", $object, $x, $y, $duration);
        } else {
            return _("wizard.command.desc.param.anim.move.to.point.with.wait::Перемещать объект {0} к точке (x: {1}, y: {2}) за {3} млсек с ожиданием окончания", $object, $x, $y, $duration);
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/moveTo16.png";
    }

    function isYield(Action $action)
    {
        return !$action->continue;
    }

    function imports(Action $action = null)
    {
        return [
            Animation::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        if ($action->continue) {
            return "Animation::moveTo({$action->get('object')}, {$action->get('duration')}, {$action->get('x')}, {$action->get('y')})";
        } else {
            return "Animation::moveTo({$action->get('object')}, {$action->get('duration')}, {$action->get('x')}, {$action->get('y')},";
        }
    }
}