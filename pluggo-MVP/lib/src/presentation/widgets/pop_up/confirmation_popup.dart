import 'package:pluggo/src/domain/exceptions/api_exception.dart';
import 'package:pluggo/src/presentation/provider/theme_provider.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/presentation/styles/styles.dart';
import 'package:pluggo/src/presentation/widgets/button/custom_loading_button.dart';
import 'package:pluggo/src/domain/enum/color_type.dart';
import 'package:pluggo/src/presentation/widgets/button/custom_rounded_loading_button.dart';
import 'package:flutter/material.dart';

import '../../../../utils/warning_messages.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class ConfirmationWithLoadingButtonPopUpWidget extends StatefulWidget {
  final PopUp popUp;
  final String confirmText;
  final String denyText;
  final Future<void> Function()? confirmFunction;
  final void Function()? denyFunction;
  final bool showConfirmButton;
  final bool showCloseButton;

  const ConfirmationWithLoadingButtonPopUpWidget({
    super.key,
    required this.popUp,
    required this.confirmText,
    required this.denyText,
    this.confirmFunction,
    this.denyFunction,
    required this.showCloseButton,
    required this.showConfirmButton,
  });

  @override
  State<ConfirmationWithLoadingButtonPopUpWidget> createState() => _ConfirmationWithLoadingButtonPopUpWidgetState();
}

class _ConfirmationWithLoadingButtonPopUpWidgetState extends State<ConfirmationWithLoadingButtonPopUpWidget> {
  bool isLoading = false;
  final CustomLoadingButtonController btnController = CustomLoadingButtonController();

  @override
  Widget build(BuildContext context) {
    return PopScope(
        canPop: !isLoading,
        child: Dialog(
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
                      widget.popUp.title,
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
                    padding: EdgeInsets.only(
                        top: HeightSpacing.medium, bottom: HeightSpacing.medium, left: WidthSpacing.medium, right: WidthSpacing.medium),
                    child: Column(children: [
                      Text(
                        widget.popUp.description,
                        style: AppTypography.popUpDescription(context),
                      ),
                      if (widget.showConfirmButton == true || widget.showCloseButton == true) SizedBox(height: HeightSpacing.medium),
                      if (widget.showConfirmButton == true)
                        CustomRoundedLoadingButton(
                            controller: btnController,
                            color: ColorType.primary,
                            onPressedCallback: widget.confirmFunction != null
                                ? () async {
                              try {
                                setState(() {
                                  isLoading = true;
                                });
                                await widget.confirmFunction!();
                                setState(() {
                                  isLoading = false;
                                });
                                appRouter.popForced(true);
                              } catch (e) {
                                setState(() {
                                  btnController.error();
                                });
                                btnController.error();
                                if (e is ApiException) {
                                  await ShowPopUp.showNotification2(e.message);
                                } else {
                                  await ShowPopUp.showNotification2(AlertMessages.genericError);
                                }
                                setState(() {
                                  isLoading = false;
                                });

                                btnController.reset();
                              }
                            }
                                : () => appRouter.maybePop(true),
                            isValid: () => true,
                            text: widget.confirmText),
                      if (widget.showCloseButton == true && !isLoading)
                        CustomRoundedLoadingButton(
                          color: ColorType.secondary,
                          border: themeNumber == 1 ? true : false,
                          onPressedCallback: widget.denyFunction ?? () => appRouter.maybePop(false),
                          isValid: () => true,
                          text: widget.denyText,
                        ),
                    ])),
              ),
            ])));
  }
}