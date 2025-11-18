//import 'package:app/src/utils/log_utils.dart';
import 'package:flutter/material.dart';

class DeviceDimensions {
  static double screenWidth = 0.0;
  static double screenHeight = 0.0;
  static double screenDensity = 0.0;

  static void init(BuildContext context) {
    //LogUtils.log('width${MediaQuery.of(context).size.width}');
    //LogUtils.log('height${MediaQuery.of(context).size.height}');
    //LogUtils.log('devicePixelRatio${MediaQuery.of(context).devicePixelRatio}');


    DeviceDimensions.screenWidth = MediaQuery.of(context).size.width;
    DeviceDimensions.screenHeight = MediaQuery.of(context).size.height;
    DeviceDimensions.screenDensity = MediaQuery.of(context).devicePixelRatio;
  }
}