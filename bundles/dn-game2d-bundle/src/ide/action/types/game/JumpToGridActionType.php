<?php
namespace ide\action\types\game;

use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class JumpToGridActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_MOVING;
    }

    function attributes()
    {
        return [
            'object' => 'object',
            'gridX' => 'integer',
            'gridY' => 'integer',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'wizard.object',
            'gridX' => 'wizard.2d.grid.x.hor::Grid X (горизонтальное выравнивание)',
            'gridY' => 'wizard.2d.grid.y.ver::Grid Y (вертикальное выравнивание)',
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'gridX' => ['def' => '1'],
            'gridY' => ['def' => '1'],
        ];
    }

    function getTagName()
    {
        return "jumpingToGrid";
    }

    function getTitle(Action $action = null)
    {
        return "wizard.2d.command.jump.to.grid::Выровнять по сетке";
    }

    function getDescription(Action $action = null)
    {
        if ($action) {
            $gridX = $action->get('gridX');
            $gridY = $action->get('gridY');

            return _("wizard.2d.command.desc.param.jump.to.grid::Выровнять {0} объект по сетке (x: {1}, y: {2})", $action->get('object'), $gridX, $gridY);
        } else {
            return "wizard.2d.command.desc.jump.to.grid::Выровнять объект по сетке";
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/gridSnap16.png';
    }

    function imports(Action $action = null)
    {
        return [
            Jumping::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $gridX = $action->get('gridX');
        $gridY = $action->get('gridY');
        $object = $action->get('object');

        return "Jumping::toGrid({$object}, $gridX, $gridY)";
    }
}