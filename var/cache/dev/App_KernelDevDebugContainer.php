<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\Container2HDk2Ex\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/Container2HDk2Ex/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/Container2HDk2Ex.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\Container2HDk2Ex\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \Container2HDk2Ex\App_KernelDevDebugContainer([
    'container.build_hash' => '2HDk2Ex',
    'container.build_id' => 'd7b7e690',
    'container.build_time' => 1687181005,
], __DIR__.\DIRECTORY_SEPARATOR.'Container2HDk2Ex');
