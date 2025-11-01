/*
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:pluggo/src/presentation/provider/theme_provider.dart';
import 'package:pluggo/src/presentation/styles/styles.dart';
import 'package:pluggo/src/presentation/widgets/button/custom_rounded_loading_button.dart';
import 'package:pluggo/src/domain/enum/color_type.dart';
import 'package:pluggo/src/utils/validators/validate_input.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import '../../../utils/timeout_popUp.dart';
import '../../../utils/timeout_popUp2.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';
import '../button/custom_loading_button.dart';
import '../input/custom_textfield.dart';
import '../input/custom_token_input.dart';

class TokenValidationPopUp extends StatefulWidget {
  final String title;
  final String subtitle;
  final String desc;
  final int seconds;
  final Future<void> Function()? confirmFunction;
  final void Function()? denyFunction;
  final bool showDenyButton;
  final TextEditingController controller;
  final GlobalKey<FormState> formKey;
  final bool? timer;

  const TokenValidationPopUp({
    super.key,
    required this.title,
    required this.subtitle,
    required this.desc,
    required this.seconds,
    this.confirmFunction,
    this.denyFunction,
    this.showDenyButton = false,
    required this.controller,
    required this.formKey,
    this.timer
  });

  @override
  State<TokenValidationPopUp> createState() => _TokenValidationPopUpState();
}

class _TokenValidationPopUpState extends State<TokenValidationPopUp> {
  bool isLoading = false;
  bool callback = false;
  bool success = false;
  int? statusCode;
  final CustomLoadingButtonController btnController = CustomLoadingButtonController();
  int attemptCount = 0;

  @override
  Widget build(BuildContext context) {

    return PopScope(
      canPop: !isLoading,
      child: Dialog(
        backgroundColor: ThemeProvider.of(context).themeColors.background,
        child:
            Stack(
          clipBehavior: Clip.none,
          alignment: Alignment.topCenter,
          children: [
               Column(
              mainAxisSize: MainAxisSize.min,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // TÍTULO
                Container(
                  padding: EdgeInsets.only(top: HeightSpacing.medium),
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      colors: [
                        ThemeProvider.of(context).themeColors.background,
                        ThemeProvider.of(context).themeColors.background,
                      ],
                    ),
                    borderRadius: BorderRadius.only(
                      topRight: Radius.circular(HeightSpacing.custom(32)),
                      topLeft: Radius.circular(HeightSpacing.custom(32)),
                    ),
                  ),
                  child: Container(
                      padding: EdgeInsets.only(
                        bottom: HeightSpacing.medium,
                        left: WidthSpacing.small,
                        right: WidthSpacing.small,
                      ),
                      alignment: Alignment.center,
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Row(children: [
                            IconButton(
                              icon: Icon(Icons.arrow_back, color: ThemeProvider.of(context).themeColors.primaryContainer), // ou qualquer cor que combine
                              onPressed: () {
                                if (!isLoading) appRouter.maybePop(false); // Impede fechar durante loading
                              },
                            ),
                            Text(
                                widget.title,
                                style: AppTypography.popUpInputTitle(context)
                            ),
                          ],),
                            SizedBox(height: HeightSpacing.small,),
                            Padding(padding: EdgeInsets.symmetric(
                              horizontal: WidthSpacing.medium,
                            ),
                            child:
                            Text(widget.subtitle, style: AppTypography.popUpDescription(context).copyWith(color:  ThemeProvider.of(context).appColors.black)),
                            )
                        ],
                      )
                  ),
                ),

                // CONTEÚDO
                ClipRRect(
                  borderRadius: BorderRadius.only(
                    bottomLeft: Radius.circular(HeightSpacing.custom(32)),
                    bottomRight: Radius.circular(HeightSpacing.custom(32)),
                  ),
                  child: Container(
                    width: MediaQuery.of(context).size.width,
                    color: ThemeProvider.of(context).themeColors.background,
                    padding: EdgeInsets.symmetric(
                      horizontal: WidthSpacing.medium,
                    ),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [

                        Text(widget.desc, style: AppTypography.popUpDescription(context)),
                        SizedBox(height: HeightSpacing.small,),
                        CustomTokenInput(
                          formKey: widget.formKey,
                          isDark: true,
                          validator: (otp) {
                            return ValidateInput.otp(otp);
                          },
                          controller: widget.controller,
                        ),
                        SizedBox(height: HeightSpacing.smallMedium),
                        widget.timer == true
                            ? TimeoutPopUpButton2(
                          timeoutDuration: widget.seconds?? 180, // 3 minutos
                          onTimeoutComplete: () async {
                            appRouter.maybePop(true); // Fecha o popup
                            return true; // Retorna true para indicar que a ação foi concluída
                          },
                        ) : SizedBox.shrink(),
                        SizedBox(height: HeightSpacing.smallMedium),

                          CustomRoundedLoadingButton(
                            controller: btnController,
                            color: ColorType.primary,
                            onPressedCallback: widget.confirmFunction != null
                                ? () async {
                              attemptCount++;
                              if (attemptCount > 3) {
                                appRouter.popForced(true); // Fecha o diálogo
                              } else {
                                try {
                                  setState(() {
                                    isLoading = true;
                                  });

                                  await widget
                                      .confirmFunction!(); // retorna statusCode

                                  setState(() {
                                    callback = true;
                                  });
                                } catch (e) {
                                  setState(() {
                                    success = false;
                                    btnController.stop();
                                    callback = true;
                                  });
                                } finally {
                                  setState(() {
                                    isLoading = false;
                                    btnController.reset();
                                  });
                                }
                              }
                            }
                                : () => appRouter.maybePop(true),
                            isValid: () => ValidateInput.otp(widget.controller.text) == null,
                            text: "Validar",
                          ),
                        SizedBox(height: HeightSpacing.medium,),

                      ],
                    ),
                  ),
                ),
              ],
            ),

          ],
        ),
      ),
    );
  }
}


 */