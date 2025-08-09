<?php

class CodeTest
{
    /**
     * @test aaaa
     */
    public function select()
    {
    }

    /** @test */
    public function update()
    {
    }

    /**
     * @test
     * @return bool
     */
    public function delete()
    {
        return true;
    }

    /**
     * @test
     * aaaaaa
     */
    public function insert()
    {
    }

    public function dataProviderMethod(): array
    {
        return [
            ['data1'],
            ['data2'],
            ['data3'],
        ];
    }

    public static function dataProviderStaticMethod(): array
    {
        return [
            ['data1'],
            ['data2'],
            ['data3'],
        ];
    }

    /**
     * @dataProvider dataProviderMethod
     * @dataProvider dataProviderStaticMethod
     */
    public function testWithDataProvider($data)
    {
    }
}
