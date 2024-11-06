<?php

namespace Core\Repositories;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Prefix
{
    public function __construct(public string $prefix)
    {
    }
}

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Middleware
{
    public function __construct(public array $middleware)
    {
    }
}

#[Attribute(Attribute::TARGET_METHOD)]
class Get
{
    public function __construct(public string $uri)
    {
    }
}

#[Attribute(Attribute::TARGET_METHOD)]
class Post
{
    public function __construct(public string $uri)
    {
    }
}

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Group
{
    public function __construct(public string $group)
    {
    }
}

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class NamespaceAttr
{
    public function __construct(public string $namespace)
    {
    }
}
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class ApiRoute
{
}

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class WebRoute
{
}
#[Attribute(Attribute::TARGET_METHOD)]
class Put
{
    public function __construct(public string $uri)
    {
    }
}

#[Attribute(Attribute::TARGET_METHOD)]
class Patch
{
    public function __construct(public string $uri)
    {
    }
}
#[Attribute(Attribute::TARGET_METHOD)]
class Delete
{
    public function __construct(public string $uri)
    {
    }
}
