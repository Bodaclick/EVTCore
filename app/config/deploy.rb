set :application, "EVTCore"
set :repository,  "git@github.com:Bodaclick/EVTCore.git"
set :scm,         :git
set :model_manager, "doctrine"

set :deploy_to,   "/var/www/#{application}"
set :app_path,    "app"

set :shared_files,      ["app/config/parameters.yml", "web/.htaccess", "web/check.txt"]
set :shared_children,     [app_path + "/logs"]

set :use_composer, true
set :vendors_mode, "install"

set :domain,      "172.26.0.11"
set :project_env, "dev"
set :user, "bdkdeploy"
set :branch, "dev"
set :clear_controllers, false

set :webserver_user,    "www-data"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set :use_sudo, false
set :keep_releases,  3
set :assets_symlinks, true
set :writable_dirs,     ["app/cache", "app/logs"]
set :permission_method, :acl
set :use_set_permissions, true
set :evt_parameters_file, "parameters_#{project_env}.yml"
set :evt_parameters_repo, "git@github.com:Bodaclick/EVTParameters.git"

namespace :evt do
  task :parameters, :roles => :app do
    capifony_pretty_print "--> Download the versionated parameters files"

    run "rm -Rf #{shared_path}/parameters"
    try_sudo "git clone #{evt_parameters_repo} #{shared_path}/parameters"
    try_sudo "mv #{shared_path}/parameters/#{application}/#{evt_parameters_file} #{shared_path}/app/config/parameters.yml"
    
    capifony_puts_ok
  end

  task :vendors, :roles => :app do
    capifony_pretty_print "--> Copying the vendors from the previous release"
    
    try_sudo "mkdir -p #{latest_release}/vendor"
    try_sudo "cp -Rf #{previous_release}/vendor/* #{latest_release}/vendor >/dev/null"
    
    capifony_puts_ok
  end

  task :assetic, :roles => :app do
    capifony_pretty_print "--> Executing assetic dump"
 
    try_sudo "rm -Rf #{latest_release}/web/css/*"
    try_sudo "rm -Rf #{latest_release}/web/js/*"
    run "cd #{latest_release} && #{php_bin} #{symfony_console} assetic:dump --env=prod "
 
    capifony_puts_ok
  end
  

end

task :setup_rabbit do
  run "sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} rabbitmq:setup-fabric'"
end

task :stats_schema_update do
  run "sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} doctrine:schema:update --em stats --force'"
end

task :compile_go do
  try_sudo "mkdir -p #{latest_release}/bin"
  run "sh -c 'export PATH=$PATH:/usr/local/go/bin && export GOPATH=#{latest_release} && cd #{latest_release}/bin && go get EMDCommunication'"
  run "sh -c 'export PATH=$PATH:/usr/local/go/bin && export GOPATH=#{latest_release} && cd #{latest_release}/bin && go get HookConsumers'"
  run "sh -c 'export PATH=$PATH:/usr/local/go/bin && export GOPATH=#{latest_release} && cd #{latest_release}/bin && go build ../src/EMDCommunication/NewShowroomSync.go'"
  run "sh -c 'export PATH=$PATH:/usr/local/go/bin && export GOPATH=#{latest_release} && cd #{latest_release}/bin && go build ../src/HookConsumers/HookConsumer.go'"
end

namespace :php_fpm do
  desc "Reload PHP5-FPM (requires sudo access to /usr/sbin/service php5-fpm reload)"
  task :reload, :roles => :app do
    run "sudo killall php5-fpm"
  end
end

before "deploy:share_childs", "evt:parameters"
before "symfony:composer:install","evt:vendors"
before "symfony:composer:update", "evt:vendors"
before "symfony:cache:warmup", "symfony:doctrine:schema:update", "stats_schema_update", "symfony:doctrine:cache:clear_query", "setup_rabbit", "compile_go", "php_fpm:reload"
after "symfony:assets:install", "evt:assetic"
