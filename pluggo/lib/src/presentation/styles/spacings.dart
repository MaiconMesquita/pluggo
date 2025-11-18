
import '../../../utils/device_dimensions.dart';

class HeightSpacing {
  static final double extraSmall = _calculateSpacing(8);
  static final double small = _calculateSpacing(12);
  static final double smallMedium = _calculateSpacing(16);
  static final double medium = _calculateSpacing(24);
  static final double mediumLarge = _calculateSpacing(36);
  static final double heightBtn = 25 + _calculateSpacing(32);
  static final double heightFormsBtn = _calculateSpacing(10);
  static final double large = _calculateSpacing(56);
  static double custom(double height) => _calculateSpacing(height);
  static double customWithFixed(double height) => _calculateSpacing(height / 2) + (height / 2);

  static double baseHeightResolution = 984.0;
  static double baseScreenDensity = 3;

  static double _calculateSpacing(double baseSize) {
    double heightRatio = DeviceDimensions.screenHeight / baseHeightResolution;
    double adjustedSpacing = baseSize * heightRatio;

    return adjustedSpacing;
  }
}

class WidthSpacing {
  static final double extraSmall = _calculateSpacing(8);
  static final double small = _calculateSpacing(12);
  static final double smallMedium = _calculateSpacing(16);
  static final double medium = _calculateSpacing(24);
  static final double mediumLarge = _calculateSpacing(36);
  static final double large = _calculateSpacing(56);
  static double custom(double width) => _calculateSpacing(width);
  static double customWithFixed(double width) => _calculateSpacing(width / 2) + (width / 2);

  static double baseWidthResolution = 432.0;
  static double baseScreenDensity = 3;

  static double _calculateSpacing(double baseSize) {
    double widthRatio = DeviceDimensions.screenWidth / baseWidthResolution;
    double adjustedSpacing = baseSize * widthRatio;

    return adjustedSpacing;
  }
}