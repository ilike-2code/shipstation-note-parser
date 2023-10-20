<?php 

use PHPUnit\Framework\TestCase;

class NoteParserTest extends TestCase 
{
    /**
     * @dataProvider parseDataProvider
     */    
    public function testParse($original, $expected_note, $expected_extra) 
    {    
        $parser = new NoteParser();
        $parser->parse($original);
        $this->assertEquals($expected_note, $parser->getNote());
        $this->assertEquals($expected_extra, $parser->getExtra());
    }

    public static function parseDataProvider()
    {
        return [
            ['special note 4 u<br/>tracking', 'special note 4 u', 'tracking'],
            ['<br/>tracking <br/> more tracking', '', 'tracking <br/> more tracking'],
            ['null<br/>tracking', '', 'tracking'],
            ['<br/>tracking', '', 'tracking'],
            ['howdy', 'howdy', ''],
            ['', '', ''],
        ];
    }
}