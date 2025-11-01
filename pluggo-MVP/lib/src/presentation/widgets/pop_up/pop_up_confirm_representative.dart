import 'package:flutter/material.dart';
//import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:auto_route/auto_route.dart';

import '../../../domain/enum/color_type.dart';
import '../../provider/theme_provider.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';
import '../button/custom_loading_button.dart';
class ConfirmRepresentativePopUpWidget extends StatelessWidget {
  final String id;
  final String? title;
  final String? socialName;

  const ConfirmRepresentativePopUpWidget({super.key, required this.id, this.title, this.socialName});

  void navigateToRepresentativeTermAcceptedScreen(BuildContext context) {
    //context.router.push(RepresentativeTermAcceptedRoute(id: id)); rota para tela de termos
    print("foi para termos");
    context.router.maybePop();
  }

  @override
  Widget build(BuildContext context) {
    return Dialog(
      backgroundColor: Colors.transparent,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Stack(
            children: [
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
                  padding: EdgeInsets.symmetric(
                    vertical: HeightSpacing.mediumLarge,
                    horizontal: WidthSpacing.small,
                  ),
                  alignment: Alignment.center,
                  child: Text(
                    'Confirmação de Sócio',
                    style: AppTypography.subtitle(context).copyWith(color: ThemeProvider.of(context).themeColors.onPrimaryContainer),
                  ),
                ),
              ),
              Positioned(
                top: 0,
                right: 0,
                child: IconButton(
                  iconSize: 24.0,
                  icon: Icon(Icons.close, color: ThemeProvider.of(context).themeColors.onPrimaryContainer),
                  onPressed: () {
                    Navigator.of(context).pop();
                  },
                ),
              ),
            ],
          ),
          ClipRRect(
            borderRadius: BorderRadius.only(
              bottomLeft: Radius.circular(HeightSpacing.custom(32)),
              bottomRight: Radius.circular(HeightSpacing.custom(32)),
            ),
            child: Container(
              width: MediaQuery.of(context).size.width,
              color: ThemeProvider.of(context).themeColors.background,
              padding: EdgeInsets.symmetric(
                vertical: HeightSpacing.medium,
                horizontal: WidthSpacing.medium,
              ),
              child: Column(
                children: [
                  Text(
                    title != null
                        //? '$title no ${dotenv.env['APP_FULL_NAME'].toString()}.'
                        ? '$title no url.'
                        : 'A empresa $socialName convidou você para se tornar um participante no url.',
                    //: 'A empresa $socialName convidou você para se tornar um participante no ${dotenv.env['APP_FULL_NAME'].toString()}.',
                    style: AppTypography.popUpDescription(context),
                  ),
                  SizedBox(height: HeightSpacing.medium),
                  CustomRoundedLoadingButton(
                    color: ColorType.primary,
                    onPressedCallback: () {
                      navigateToRepresentativeTermAcceptedScreen(context);
                    },
                    isValid: () => true,
                    text: 'Abrir termo',
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}