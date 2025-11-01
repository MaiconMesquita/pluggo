import '../domain/entity/themes.dart';
import '../presentation/styles/styles.dart';

const int themeNumber = 1;

CustomTheme get defaultTheme => CustomTheme(
  appColors: appColors,
  themeColors: defaultColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: true,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);

CustomTheme get secondaryTheme => CustomTheme(
  appColors: appColors,
  themeColors: secondaryColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: true,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);

CustomTheme get menuTheme => CustomTheme(
  appColors: appColors,
  themeColors: menuColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: true,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);
CustomTheme get loginTheme => CustomTheme(
  appColors: appColors,
  themeColors: loginColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: false,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);
CustomTheme get mainTheme => CustomTheme(
  appColors: appColors,
  themeColors: mainColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: true,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);
CustomTheme get valueTheme => CustomTheme(
  appColors: appColors,
  themeColors: valueColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: true,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);
CustomTheme get statementTheme => CustomTheme(
  appColors: appColors,
  themeColors: statementColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: true,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);

CustomTheme get signupTheme => CustomTheme(
  appColors: appColors,
  themeColors: signupColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: true,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);

CustomTheme get cameraTheme => CustomTheme(
  appColors: appColors,
  themeColors: cameraColors,
  sizes: themeSizes,
  typography: typography,
  hasFooter: false,
  alwaysShowLoginBackground: false,
  hasInputLabel: true,
  hasInputPrefix: true,
);

ThemeTypography get typography => ThemeTypography(
  bodyFontFamily: 'Gilroy',
  titleFontFamily: 'Gilroy',
  subtitleFontFamily: 'Gilroy',
  tokenFontFamily: 'Ubuntu',
);

ThemeSizes get themeSizes => ThemeSizes(
  borderRadius: 32.0,
  loginRadius: 500.0,
  inputBorderSize: 1.0,
  valueFontSize: 20.0,
  numericKeyboardRadius: 80.0,
  elevation: 2.0,
  iconSize: 24.0,
  bottomIconSize: 75.0,
  iconSizeHeader: 50.0,
  extraSmall: 8.0,
  small: 12.0,
  smallMedium: 18.0,
  medium: 24.0,
  mediumLarge: 36.0,
  large: 56.0,
  titleFontSize: 20.0,
  newTitle: 35,
  formsSubTitleFontSize: 22.0,
  subtitleFontSize: 18.0,
  mediumFontSize: 16.0,
  smallFontSize: 14.0,
  buttonHeight: 48.0,
  inputHeight: 48.0,
  minFontScaleFactor: 0.8,
  maxFontScaleFactor: 1.2,
  baseScreenWidth: 375.0,
  baseScreenHeight: 812.0,
  loginGreeting: 68.0,
  loginName: 50,
  btnText: 25.0,
);