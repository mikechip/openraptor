$(function(){
   
    var chat = $('#chat')[0]; // Окно чата
    var form = $('#chat-form')[0]; // форма
    
    // вешаем обработчик на отправку формы
    $('#chat-form').submit(function(event){
        
        // поле ввода
        var text = $(form).find('input[type="text"]');

        // выключаем форму пока не пришел ответ
        $(form).find('input').attr("disabled", true);
        
		// если команда, то обрабатываем как команду
		if(text.val() && text.val().indexOf("/")+1) {
			$.get(
				'/api?a=handledino&string='+ text.val(),
				{}, 
				function (data) {
					 var msg = $('<div>' + data + '</div>');
                      $(chat).append(msg);
					  $(form).find('input').attr("disabled", false);
				},
				'text'
			);
			return false;
		}
		else {
			// отправка сообщения
			update(text);
			return false;
		}
    });
    
    function update(text) {
        // что шлём
        var send_data = { last_id: $(chat).attr('data-last-id') };
        if (text)
            send_data.text = $(text).val();
        // шлём запрос
        $.post(
            '/messager/handler',
            send_data, // отдаём скрипту данные
            
            function (data) {
                data = eval('(' + data + ')');
                // ссылка пришла?
                if (data && $.isArray(data)) {
                    $(data).each(function (k) {

                        var msg = $('<div><a target="_blank" href="/player/' + data[k].user + '">' + data[k].user + '</a>: ' + data[k].text + '</div>');
                        // и цепляем его к чату
                        $(chat).append(msg);
                        // если ласт ид меньше пришедшего
                        if (parseInt($(chat).attr('data-last-id')) < data[k].date)
                            // запоминаем новый ласт ид
                            $(chat).attr('data-last-id', data[k].date);
                    });
                    
                    // если это отправка, то при получении ответа, включаем форму
                    if (text) {
                        // включаем форму
                        $(form).find('input').attr("disabled", false);
                        // и очищаем текст
                        $(text).val('');
						
                    }

                    // прокрутка
                    $(chat).scrollTop(chat.scrollHeight);

                    // обновим таймер 
                    update_timer();
                }
				},
            'text' // полученные данные рассматривать как текст, а потом обращать в JSON, во избежании проблем с кодировкой и BOM
        );
    }

    // что бы при загрузке получить данные в чат, вызываем сразу апдейт
    update();
    
    // что бы окно чата обновлялось раз в секунду, прицепим таймер
    var timer;
    function update_timer() {
        if (timer) // если таймер уже был, сбрасываем
            clearTimeout(timer);
        timer = setTimeout(function () {
            update();
        }, 1000);
    }
    update_timer();
});