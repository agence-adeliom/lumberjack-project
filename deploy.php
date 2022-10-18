<?php
namespace Deployer;

require 'recipe/wordpress.php';

// Project repository
$repo = null;
if($origin = exec("git config --get remote.origin.url")){
    $repo = trim(parse_url($origin, PHP_URL_PATH), '/');
    $repo = str_replace("git@github.com:", "", $repo);
    $repo = substr($repo, 0, strpos($repo, ".git"));
}

set('repository_name', $repo);
set('repository', 'git@github.com:{{repository_name}}.git');
set('theme', "adeliom");


set('shared_dirs', ['web/app/uploads', 'web/app/sessions']);
set('writable_dirs', ['web/app/uploads', 'web/app/sessions']);
set('writable_mode', "chmod");
set('writable_recursive', true);


// Hosts
import('.inventory.yaml');

// Tasks
task('dotenv:set-env', static function (): void {
    if(test("[ -f {{release_path}}/.env.local ]")){
        run('rm {{release_or_current_path}}/.env.local');
    }
    run('touch {{release_or_current_path}}/.env.local');
    run('echo "WP_ENV={{app_env}}" >> {{release_or_current_path}}/.env.local');

    run(sprintf('cd {{release_or_current_path}} && {{bin/composer}} config http-basic.gf-composer-proxy.arnaud-ritti.workers.dev licensekey %s', base64_decode('MDYxNjhkYzA2YzQ1NWRjYWVkNmU0NzM2Y2EzNTBkMjg=')));
    run(sprintf('cd {{release_or_current_path}} && {{bin/composer}} config http-basic.auth-acf-composer-proxy.pivvenit.net licensekey %s', base64_decode('YjNKa1pYSmZhV1E5TkRBMk9UWjhkSGx3WlQxa1pYWmxiRzl3WlhKOFpHRjBaVDB5TURFMExUQTVMVEkySURFek9qVXpPakkx')));
});

task('npm:build', static function (): void {
    if(commandExist('npm')){
        run('cd {{release_or_current_path}}/web/app/themes/{{theme}} && npm install && npm run build:production');
    }else{
        runLocally('cd web/app/themes/{{theme}} && npm install && npm run build:production');
        upload('web/app/themes/{{theme}}/build/', '{{release_or_current_path}}/web/app/themes/{{theme}}/build/');
    }
});

task('upload:medias', static function (): void {
    upload('web/app/uploads/', '{{release_or_current_path}}/web/app/uploads/');
});

task('download:medias', static function (): void {
    download('{{release_or_current_path}}/web/app/uploads/', 'web/app/uploads/');
});

after('deploy:update_code', 'deploy:vendors');
before('deploy:vendors', 'dotenv:set-env');
before('deploy:symlink', 'npm:build');

after('deploy:failed', 'deploy:unlock');
