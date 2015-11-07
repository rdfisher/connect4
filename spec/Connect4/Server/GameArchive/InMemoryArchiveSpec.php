<?php

namespace spec\Connect4\Server\GameArchive;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Connect4\Lib\Board;
use Connect4\Server\GameArchive\ArchivedGame;
use DateTime;

class InMemoryArchiveSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Connect4\Server\GameArchive\InMemoryArchive');
    }
    
    function it_can_archive_a_game(Board $board, DateTime $date)
    {
        $id = $this->archive($board, 'Jim', 'Bob', $date);
        $id->shouldBe(1);
        $archivedGame = new ArchivedGame($board->getWrappedObject(), 'Jim', 'Bob', $date->getWrappedObject());
        $this->getArchive()->shouldBeLike([$archivedGame]);
        $this->getById(1)->shouldBeLike($archivedGame);
    }
    
    function it_can_return_a_slice_of_games(
        Board $board1, 
        DateTime $date1, 
        Board $board2, 
        DateTime $date2, 
        Board $board3, 
        DateTime $date3
    ) {
        $this->archive($board1, 'Jim', 'Bob', $date1)->shouldBe(1);
        $this->archive($board2, 'Steve', 'Amy', $date2)->shouldBe(2);
        $this->archive($board3, 'Sarah', 'Jim', $date3)->shouldBe(3);
        $this->getArchiveSince(1)->shouldHaveCount(2);
    }
}
