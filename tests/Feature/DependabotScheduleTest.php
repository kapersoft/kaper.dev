<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

test('dependabot version updates run daily', function (): void {
    /** @var array{version: int, updates: list<array{package-ecosystem: string, schedule: array{interval: string, day?: string}}>} $config */
    $config = Yaml::parseFile(base_path('.github/dependabot.yml'));

    expect($config['version'])->toBe(2);

    $ecosystems = [];

    foreach ($config['updates'] as $update) {
        expect($update['schedule']['interval'])->toBe('daily');
        expect($update['schedule'])->not->toHaveKey('day');
        $ecosystems[] = $update['package-ecosystem'];
    }

    expect($ecosystems)->toEqualCanonicalizing(['composer', 'github-actions']);
});

test('weekly deployment workflow is not present', function (): void {
    expect(file_exists(base_path('.github/workflows/weekly-deployment.yml')))->toBeFalse();
});
