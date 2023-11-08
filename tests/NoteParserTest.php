<?php 

use PHPUnit\Framework\TestCase;

class NoteParserTest extends TestCase 
{
    /**
     * @dataProvider parseDataProvider
     */    
    public function testParse($original, $note, $extra) 
    {    
        $parser = new NoteParser();
        $parser->parse($original);
        $this->assertEquals($note, $parser->getNote());
        $this->assertEquals($extra, $parser->getExtra());
    }

    public static function parseDataProvider()
    {
        return [
            [
                'original' => 'special note 4 u<br/>tracking',
                'note' => 'special note 4 u',
                'extra' => 'tracking'
            ],
            [
                'original' => '<br/>tracking <br/> more tracking',
                'note' => ' ',
                'extra' => 'tracking <br/> more tracking',
            ],
            [
                'original' => 'null<br/>tracking',
                'note' =>  ' ',
                'extra' => 'tracking',
            ],
            [
                'original' => '<br/>tracking',
                'note' =>  ' ',
                'extra' => 'tracking',
            ],
            [
                'original' => 'howdy',
                'note' => 'howdy',
                'extra' => '',
            ],
            [
                'original' => '',
                'note' => ' ',
                'extra' => '',
            ],
        ];
    }
}