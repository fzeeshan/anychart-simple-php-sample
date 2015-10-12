Vagrant.configure("2") do |config|
  config.ssh.forward_agent = true
  config.vm.box = "ubuntu/trusty64"
  config.vm.network :private_network, ip: "192.168.50.4"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.provision :shell, :path => File.join( "provision", "provision.sh")
end
