<?php
  // Chat system errors
  $Errors[101] = "Няма такъв потребител!";
  $Errors[102] = "Грешна парола!";
  $Errors[103] = "Не можете да разглеждате тази страница преди да сте се удостоверили!";
  $Errors[104] = "Не можете да редактирате суперпотребителя!";
  $Errors[105] = "Не можете да изтриете суперпотребителя!";
  $Errors[106] = "Не можете да изтриете самия себе си!";
  $Errors[107] = "Съществува потребител със същото име!";
  $Errors[108] = "Съществува потребител със същия псевдоним!";
  $Errors[109] = "Съществува цвят със същото име!";
  $Errors[110] = "Съществува стая със същото име!";
  $Errors[111] = "Не мога да Ви въведа в административната система.";
  $Errors[112] = "Не мога да Ви въведа в стаята за разговори.";
  $Errors[151] = "Не мога да обработя вашата заявка! Моля, опитайте отново по-късно.";
  // Database server errors
  $Errors[201] = "Не може да бъде установена връзка със сървъра на базата данни!";
  $Errors[202] = "Не може да бъде използвана базата данни!";
  $Errors[203] = "Грешка при изпълнение на заявка към базата данни!";

  function PrintError($Number) {
    global $Errors;
    print("<html>\n");
    print("<head>\n");
    print("<title>Error</title>\n");
    print("<meta http-equiv=\"Centent-type\" content=\"text/html;");
    print(" charset=windows-1251\" />\n");
    print("</head>\n");
    print("<body>\n");
    print("<p align=\"left\" style=\"font-size: 22pt;\"><b>");
    print("Грешка $Number</b></p>\n");
    print("<p align=\"left\">".$Errors[$Number]."</p>\n");
    print("<hr>\n");
    print("<p align=\"left\"><i>Страницата е създадена ");
    print(date("Y-m-d H:i:s @B")." от ".CHAT_NAME." ".CHAT_VERSTR);
    print("</body>\n");
    print("</html>\n");
  }
?>
