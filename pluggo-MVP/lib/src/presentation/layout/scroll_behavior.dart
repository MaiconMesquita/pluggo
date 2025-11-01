import 'package:flutter/material.dart';

//tira os efeitos do indicador de limite de rolagem padrão do dispositivo

class MyBehavior extends ScrollBehavior {
  @override
  Widget buildOverscrollIndicator(BuildContext context, Widget child, ScrollableDetails details) {
    return child;
  }
}