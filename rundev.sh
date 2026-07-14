#!/bin/bash

build(){ 
      sudo docker compose -f docker-compose-dev.yml up --build -d
     
}

start(){
      echo "Starting the server" 
      build
      up
      sleep 10
      migrate 
      seed
      echo "Server successfuly started. Enjoy development!" 
}
seed(){
  sudo docker exec -it erp php artisan db:seed
}
migrate(){
      sudo docker exec -it erp composer dump-autoload
      sudo docker exec -it erp php artisan migrate	 	
}

stop(){
      sudo docker compose -f docker-compose-dev.yml stop 
}
up(){
      sudo docker compose -f docker-compose-dev.yml up -d
}

down(){ 
      sudo docker compose -f docker-compose-dev.yml down
}

bash(){
  sudo docker exec -it erp /bin/bash
}

case $1 in
    start)
      start
      ;; 
    build)
      build
      ;;
    stop)
      stop
      ;;
    up)
      up
      ;;
    migrate)
      migrate
      ;;  
    down)
      down 
      ;;
    seed)
      seed 
      ;; 
    bash)
      bash 
      ;;   
    *)
      echo "Invalid argument: $1"
      echo "QUITTING!"
      exit 1
      ;;
esac                