import 'package:flutter/material.dart';

class ScrollPositionController {
  final ScrollController scrollController = ScrollController();
  double scrollPosition = 0;

  ScrollPositionController() {
    scrollController.addListener(_saveScrollPosition);
  }

  void _saveScrollPosition() {
    scrollPosition = scrollController.offset;
  }
}