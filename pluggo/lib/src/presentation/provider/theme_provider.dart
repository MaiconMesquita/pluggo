//isso da a alternativa pro usuario de ter o app escuro ou claro

import '../../domain/entity/themes.dart';
import 'package:flutter/material.dart';

class ThemeProvider extends InheritedWidget {
  final CustomTheme theme;

  const ThemeProvider({
    super.key,
    required this.theme,
    required super.child,
  });

  static CustomTheme of(BuildContext context) {
    final ThemeProvider? result = context.dependOnInheritedWidgetOfExactType<ThemeProvider>();
    assert(result != null, 'No ThemeProvider found in context');
    return result!.theme;
  }

  @override
  bool updateShouldNotify(ThemeProvider oldWidget) => theme != oldWidget.theme;
}