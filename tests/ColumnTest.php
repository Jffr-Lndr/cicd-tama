<?php
require_once '.\Column.php';
use PHPUnit\Framework\TestCase;

final class ColumnTest extends TestCase
{
    public function testConstruct(): void
    {
        $column = new Column("id", "int unsigned", "not null auto_increment");

        $this->assertInstanceOf(Column::class, $column);
    }
}



