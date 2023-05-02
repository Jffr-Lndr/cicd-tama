<?php
use PHPUnit\Framework\TestCase;
use App\Column;

final class ColumnTest extends TestCase
{
    public function testConstruct(): void
    {
        $column = new Column("id", "int unsigned", "not null auto_increment");

        $this->assertInstanceOf(Column::class, $column);
    }
}



