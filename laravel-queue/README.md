# Laravel Queue

## Redis를 Queue Connection으로 사용하기

`.env`의 `QUEUE_CONNECTION` 를 redis 로 변경한다.

`PhpRedis` PHP Extension을 설치하지 않았을 경우,
`REDIS_CLIENT=predis` 을 추가한다.
-> Laravel 7.X 버전에서 .env의 REDIS_CLIENT 키가 없어졌으며,<br/>
`predis` 패키지는 현재 마지막 커밋이 2017년 7월으로 지원이 중단된 상태이므로, 추후 Laravel에서도 제거될 수 있다고 라라벨 공식 문서에 기재되어있다. 


## Que worker 실행
```shell
$ php artisan queue:work

# or

$ php artisan queue:listen
```

## 재시도 횟수 제한

dispatch 되는 부분에서 exception이 발생할 경우, 재시도 횟수를 지정 하지 않으면,
계속해서 재시도하게 된다.

이때 아래와 같이 `tries` 옵션을 이용하여, 재시도 횟수를 조절한다.

```shell
$ php artisan queue:work --tries=3
```

## 대기열 분리

`job->onQueue()`를 이용하여, 어떤 대기열에 두고 실행할지 설정 할 수 있다.

queue worker별로 실행하고자 하는 대기열 그룹을 지정해서 실행 할 수 있다. 

horizon으로 실행하였을때 로컬에서 실행대상의 기본값은 default이고,

`config/horizon.php` 파일의 `environments.local.supervisor-1.queue`의 값을 변경하여,<br/>
여러개의 실행 대상 그룹을 지정할 수 있다. 


## "SQLSTATE[42S02]: Base table or view not found: 1146 Table 'homestead.jobs' doesn't exist"

.env 파일의 `QUEUE_CONNECTION` 를 database로 설정시, 위와 같은 오류가 발생한다면,<br/>
Exception 메세지에도 나와 있지만, 해당 테이블이 없어서 발생하는 문제이다.

이때는 아래 명령을 쉘에서 실행하여,<br/>
job 테이블을 생성하는 migration 파일을 만드는 명령을 호출하고, 마이그레이션을 실행한다.

```shell
$ php artisan queue:table
$ php artisan migrate
```

물론 6버전 이후로는 Whoops 오류 페이지에서 바로 수정 가능한 기능을 제공하고 있다. 
-> [ignition](https://github.com/facade/ignition) 이라는 훌륭한 기능으로,<br/>
Laravel6에서는 기본 에러페이지이고, 5.5이후부터 사용 가능하다고 한다.
