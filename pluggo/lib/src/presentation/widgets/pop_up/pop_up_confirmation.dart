import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';

import '../../../../utils/warning_messages.dart';
import '../../../config/theme.dart';
import '../../../domain/enum/color_type.dart';
import '../../provider/theme_provider.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';
import '../button/custom_loading_button.dart';

class ConfirmationPopUpWidget extends StatelessWidget {
  final PopUp popUp;
  final bool confirmButton;
  final bool closeButton;
  final String confirmText;
  final String closeText;

  const ConfirmationPopUpWidget({
    super.key,
    required this.popUp,
    required this.confirmButton,
    required this.closeButton,
    required this.confirmText,
    required this.closeText,
  });

  @override
  Widget build(BuildContext context) {
    return Dialog(
        backgroundColor: Colors.transparent,
        child: Column(mainAxisSize: MainAxisSize.min, children: [
          Container(
              padding: EdgeInsets.symmetric(horizontal: WidthSpacing.small),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    ThemeProvider.of(context).appColors.primary,
                    ThemeProvider.of(context).appColors.primary,
                  ],
                  begin: Alignment.centerLeft,
                  end: Alignment.centerRight,
                ),
                borderRadius: BorderRadius.only(
                  topRight: Radius.circular(HeightSpacing.custom(32)),
                  topLeft: Radius.circular(HeightSpacing.custom(32)),
                ),
              ),
              child: Container(
                padding: EdgeInsets.only(
                    top: HeightSpacing.mediumLarge, bottom: HeightSpacing.medium, left: WidthSpacing.small, right: WidthSpacing.small),
                alignment: Alignment.center,
                child: Text(
                  popUp.title,
                  style: AppTypography.subtitle(context).copyWith(color: ThemeProvider.of(context).themeColors.onPrimaryContainer),
                ),
              )),
          ClipRRect(
            borderRadius: BorderRadius.only(
              bottomLeft: Radius.circular(HeightSpacing.custom(32)),
              bottomRight: Radius.circular(HeightSpacing.custom(32)),
            ),
            child: Container(
                width: MediaQuery.of(context).size.width,
                color: ThemeProvider.of(context).themeColors.background,
                padding:
                EdgeInsets.only(top: HeightSpacing.medium, bottom: HeightSpacing.medium, left: WidthSpacing.medium, right: WidthSpacing.medium),
                child: Column(children: [
                  Text(
                    popUp.description,
                    style: AppTypography.popUpDescription(context),
                  ),
                  if (confirmButton == true || closeButton == true) SizedBox(height: HeightSpacing.medium),
                  if (confirmButton == true)
                    CustomRoundedLoadingButton(
                      color: ColorType.primary,
                      onPressedCallback: () {
                        context.router.maybePop(true);
                      },
                      isValid: () => true,
                      text: confirmText,
                    ),
                  if (closeButton == true)
                    CustomRoundedLoadingButton(
                      color: ColorType.secondary,
                      border: themeNumber == 1 ? true : false,
                      onPressedCallback: () {
                        context.router.maybePop(false);
                      },
                      isValid: () => true,
                      text: closeText,
                    ),
                ])),
          ),
        ]));
  }
}