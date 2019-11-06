<?php declare(strict_types=1);

namespace SwoftElaticsearch\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandArgument;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Input\Input;
use function output;

/**
 * Class EsCommand
 *
 * @since 2.0
 *
 * @Command(name="es",coroutine=true)
 */
class EsCommand
{
    /**
     * es 导入记录
     *
     * @CommandMapping()
     * @CommandArgument("model", type="path", mode=1,
     *     desc="你要导入的模型路径"
     * )
     * @example
     *   {binFile} import App\Model\Entity\Resource
     * @param Input $input
     */
    public function import(Input $input) {
        $modelStr = $input->get('model');
        $model = new $modelStr();
        $model->createIndex()->makeAllIndex();
        output()->writeln('执行Es 导入' . $modelStr);
    }
}
