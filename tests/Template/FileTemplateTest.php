<?php namespace DCarbone\PHPClassBuilder\Tests\Template;

/*
 * Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use DCarbone\PHPClassBuilder\Template\ClassFileTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class FileTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template
 */
class FileTemplateTest extends TestCase {
    public function testCanConstructTemplate() {
        $file = new ClassFileTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\ClassFileTemplate', $file);
    }
}
