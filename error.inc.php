<?php
  // Chat system errors
  $Errors[101] = "���� ����� ����������!";
  $Errors[102] = "������ ������!";
  $Errors[103] = "�� ������ �� ����������� ���� �������� ����� �� ��� �� ������������!";
  $Errors[104] = "�� ������ �� ����������� ����������������!";
  $Errors[105] = "�� ������ �� �������� ����������������!";
  $Errors[106] = "�� ������ �� �������� ����� ���� ��!";
  $Errors[107] = "���������� ���������� ��� ������ ���!";
  $Errors[108] = "���������� ���������� ��� ����� ���������!";
  $Errors[109] = "���������� ���� ��� ������ ���!";
  $Errors[110] = "���������� ���� ��� ������ ���!";
  $Errors[111] = "�� ���� �� �� ������ � ����������������� �������.";
  $Errors[112] = "�� ���� �� �� ������ � ������ �� ���������.";
  $Errors[151] = "�� ���� �� �������� ������ ������! ����, �������� ������ ��-�����.";
  // Database server errors
  $Errors[201] = "�� ���� �� ���� ���������� ������ ��� ������� �� ������ �����!";
  $Errors[202] = "�� ���� �� ���� ���������� ������ �����!";
  $Errors[203] = "������ ��� ���������� �� ������ ��� ������ �����!";

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
    print("������ $Number</b></p>\n");
    print("<p align=\"left\">".$Errors[$Number]."</p>\n");
    print("<hr>\n");
    print("<p align=\"left\"><i>���������� � ��������� ");
    print(date("Y-m-d H:i:s @B")." �� ".CHAT_NAME." ".CHAT_VERSTR);
    print("</body>\n");
    print("</html>\n");
  }
?>
