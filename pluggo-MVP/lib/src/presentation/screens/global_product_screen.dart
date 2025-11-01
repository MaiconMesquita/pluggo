import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/config/routes/app_routes.gr.dart';
import 'package:pluggo/src/presentation/layout/layout_list.dart';
import 'package:pluggo/src/presentation/widgets/appbar/header_default.dart';
import 'package:pluggo/src/presentation/widgets/product_list/global_product_list_style.dart';
import 'package:provider/provider.dart';

import '../../config/colors.dart';
import '../../config/theme.dart';
import '../../infra/dto/product_dto.dart';
import '../../provider/user_provider.dart';
import '../layout/layout_main.dart';
import '../provider/main_provider.dart';
import '../provider/theme_provider.dart';
import '../styles/spacings.dart';
import '../styles/typography.dart';
import '../widgets/appbar/header_mainpage.dart';
import '../widgets/product_list/product_list_style.dart';

@RoutePage()
class GlobalProductScreen extends StatefulWidget {
  const GlobalProductScreen({super.key});

  @override
  State<GlobalProductScreen> createState() => _GlobalProductScreenState();
}

class _GlobalProductScreenState extends State<GlobalProductScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      Provider.of<MainProvider>(context, listen: false).globalList();
    });
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<ProductProvider>(context, listen: true);

    return ThemeProvider(
      theme: defaultTheme,
      child: Consumer<MainProvider>(
        builder: (context, provider, child) {
          return LayoutList(
            appBar: const HeaderDefault(),
            isDarkBackground: false,
            isLoading: provider.isLoading,
            children: [
                SizedBox(height: HeightSpacing.medium),
          Column(
                    children: [
                      InkWell(
                        onTap: () async {
                          appRouter.push(CreateProductRoute());
                        },
                        child: Container(
                          padding: EdgeInsets.symmetric(horizontal: 30),
                          width: WidthSpacing.baseWidthResolution,
                          height: HeightSpacing.custom(90),
                          decoration: BoxDecoration(
                            color: appColors.light,
                            borderRadius: BorderRadius.circular(15),
                          ),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                "Adicionar produto",
                                style: AppTypography.infoItemBrandsWhite(context),
                              ),
                              Center(
                                child: Icon(
                                  Icons.add,
                                  size: HeightSpacing.custom(10) + 25,
                                  color: appColors.white,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),

                      SizedBox(height: HeightSpacing.medium),
              Text('data'),
                      Container(
                        padding: EdgeInsets.all(HeightSpacing.smallMedium),
                        height: HeightSpacing.custom(400),
                        decoration: BoxDecoration(
                          color: ThemeProvider.of(context).appColors.white,
                          borderRadius: BorderRadius.circular(HeightSpacing.custom(20)),
                        ),
                        child: GlobalProductListWidget(),
                      ),
                    ],
                  ),
              ],
          );
        },
      ),
    );
  }
}
