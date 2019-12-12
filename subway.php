<?php

/**
 * Требуется версия PHP не ниже 7.4
 *
 * @author Dmitry Fomin
 */

declare(strict_types=1);

interface EnterTurniket
{
    public function enterSubway() : void;
}

interface EnterExitTurniket extends EnterTurniket
{
    public function exitSubway() : void;
}

abstract class Turniket
{

    /* final static int ENTER_COST */
    const ENTER_COST = 30; // Стоимость прохода

    /* final static int WAIT_ENTER */
    const WAIT_ENTER = 2; // время в сек., пока турникет открыт

    /**
     * Работа турникета
     *
     * @param int $num
     * @param string $message - Сообщение
     */
    public function turniketAction(int $num, string $message) : void
    {
        echo self::ENTER_COST . ' руб. списаны со счёта.' . PHP_EOL;
        echo 'Открывается турникет №' . $num . ' ...' . PHP_EOL;
        echo $message . PHP_EOL;
        \sleep(self::WAIT_ENTER); // открыт определённое кол-во секунд
        echo 'Турникет №' . $num . ' закрывается.' . PHP_EOL;
    }

}

/**
 * Class Turniket1
 *
 * Поведение турникета №1
 */
class Turniket1 extends Turniket implements EnterTurniket
{

    /**
     * Метод входа в первый турникет
     *
     * @Override
     * @return void
     */
    public function enterSubway() : void
    {
        parent::turniketAction(1, 'ДОБРО ПОЖАЛОВАТЬ!');
    }
}

/**
 * Class Turniket2
 *
 * Поведение турникета №2
 */
class Turniket2 extends Turniket implements EnterExitTurniket
{

    /**
     * Метод входа во второй турникет
     *
     * @Override
     * @return void
     */
    public function enterSubway() : void
    {
        parent::turniketAction(2, 'ДОБРО ПОЖАЛОВАТЬ!');
    }

    /**
     * Метод выхода через второй турникет
     *
     * @Override
     * @return void
     */
    public function exitSubway() : void
    {
        parent::turniketAction(2, 'ДО СВИДАНИЯ!');
    }

}

/**
 * Class Program
 *
 * Основное состояние и поведение программы при запуске
 */
class Program
{
    private float $balance = 20.59; // баланс карты по умолчанию для проверки пополнения
    private int $cost = Turniket::ENTER_COST;

    /**
     * Метод получения текущего баланса
     *
     * @return float
     */
    private function getBalance() : float
    {
        return $this->balance;
    }

    /**
     * Основной метод при запуске
     *
     * @return void
     */
    public function main() : void
    {
        $balance = $this->getBalance();
        $cost = $this->cost;

        echo str_repeat('=', 30) . PHP_EOL;
        echo 'Баланс: ' . $balance . ' руб.' . PHP_EOL;
        echo str_repeat('=', 30) . PHP_EOL;

        while ($balance < $cost):
            echo 'Недостаточно средств! Пополните карту.' . PHP_EOL;
            $sum = readline('Введите сумму: ');
            if (! is_numeric($sum)) {
                continue;
            }
            $balance += abs((float)$sum);
            echo 'Баланс пополнен. Доступно ' . $balance . ' руб.' . PHP_EOL;
        endwhile;

        Choose:
        $startTurniket = readline('Выберите турникет для входа (1 или 2): ');

        switch ($startTurniket):

            case 1:
                $balance -= $cost;
                (new Turniket1())->enterSubway();
                echo 'Баланс: ' . $balance . ' руб.' . PHP_EOL;
                exit('Счастливого пути!' . PHP_EOL);
                break;

            case 2:
                Action:
                $action = readline('1 - Войти в метро. 2 - Выйти из метро): ');

                switch ($action):

                    case 1:
                        $balance -= $cost;
                        (new Turniket2())->enterSubway();
                        echo 'Баланс: ' . $balance . ' руб.' . PHP_EOL;
                        exit('Счастливого пути!' . PHP_EOL);
                        break;

                    case 2:
                        $balance -= $cost;
                        (new Turniket2())->exitSubway();
                        echo 'Баланс: ' . $balance . ' руб.' . PHP_EOL;
                        exit('Счастливого пути!' . PHP_EOL);
                        break;

                    default:
                        goto Action;

                endswitch;

            default:
                goto Choose;

        endswitch;
    }
}

PHP_VERSION >= 7.4 ? (new Program())->main() : exit('Требуется версия PHP не ниже 7.4');
