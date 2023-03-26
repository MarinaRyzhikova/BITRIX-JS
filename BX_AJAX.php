<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); //подключаем пролог ядра bitrix

$APPLICATION->SetTitle("AJAX"); //устанавливаем заголовок страницы

CJSCore::Init(array('ajax')); //подключаем библиотеку Bitrix JS и расширение ajax

$sidAjax = 'testAjax'; //создаем переменную

if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
    $GLOBALS['APPLICATION']->RestartBuffer();
    echo CUtil::PhpToJSObject(array(
        'RESULT' => 'HELLO',
        'ERROR' => ''
    ));
    die();
} /*проверяем, если переменная $_REQUEST['ajax_form'] отличная от нуля и она же равна переменной 'testAjax', тогда с помощью $GLOBALS['APPLICATION']->RestartBuffer(); очищам экран хедера и футера, после этого приобрауем массим в в js и выводи на экран, потом прекращаем выполнение этого скрипта*/
?>

    <div class="group">
        <div id="block"></div >
        <div id="process">wait ... </div >
    </div>
    <script>
        window.BXDEBUG = true; //подключаем режим отладки(я не уверена)
        function DEMOLoad(){
            BX.hide(BX("block"));
            BX.show(BX("process"));
            BX.ajax.loadJSON(
                '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
                DEMOResponse
            );
        }
        /*создаем функцию DEMOLoad которая будет сначала скрывать div с  id="block", потом  показывать div с  id="process", после чего загрузит  json-объект с с урла, который находиться в тут$APPLICATION->GetCurPage()?>?ajax_form==$sidAjax (но эту запись я не совмем поняла) */

        function DEMOResponse (data){
            BX.debug('AJAX-DEMOResponse ', data); //не нашла что она делает
            BX("block").innerHTML = data.RESULT; // в div с id="block" выводим инфо из data.RESULT
            BX.show(BX("block")); // показываем div с id="block"
            BX.hide(BX("process")); // скрываем  div с id="process"

            BX.onCustomEvent(
                BX(BX("block")),
                'DEMOUpdate'
            );/*создаем функцию DEMOLoad которая будет сначала скрывать div с  id="block", потом  показывать div с  id="process", после чего загрузит  json-объект с с урла, который находиться в вызываем кастомный обработчик для div с id="block" (что этот обработчик делает, я не понимаю) */
        }


        BX.ready(function(){
            /*
            BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
               window.location.href = window.location.href;
            });
            */
            BX.hide(BX("block"));// скрываем  div с id="block"
            BX.hide(BX("process")); // скрываем  div с id="process"

            BX.bindDelegate(
                document.body, 'click', {className: 'css_ajax' },
                function(e){
                    if(!e)
                        e = window.event;

                    DEMOLoad();
                    return BX.PreventDefault(e);
                }
            );// устанавливает обработчик при клике на элемент с классом css_ajax и отменяет событие по умолчанию

        });
        /*добавляем обработчик для DOM-структуры, */

    </script>
    <div class="css_ajax">click Me</div>
<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");//подключаем эпилог ядра bitrix
?>