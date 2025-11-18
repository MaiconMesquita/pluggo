import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:pluggo/src/presentation/layout/layout_default1.dart';
import 'package:pluggo/src/presentation/widgets/appbar/header_default.dart';
import 'package:provider/provider.dart';
import '../../config/colors.dart';
import '../../config/theme.dart';
import '../../provider/user_provider.dart';
import '../layout/layout_list.dart';
import '../layout/layout_main.dart';
import '../provider/main_provider.dart';
import '../provider/theme_provider.dart';
import '../styles/spacings.dart';
import '../styles/typography.dart';
import '../widgets/appbar/header_mainpage.dart';

@RoutePage()
class ProductDetailsScreen extends StatelessWidget {
  const ProductDetailsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<MainProvider>();
    final product = provider.productDetail;

    final theme = ThemeProvider.of(context);
    final appColors = theme.appColors;
    final themeColors = theme.themeColors;

    return ThemeProvider(
      theme: defaultTheme,
      child: LayoutList(
          appBar: HeaderDefault(
            color: ThemeProvider.of(context).appColors.primary,
            title: product?.name ?? 'Produto sem nome',
            backButton: true,
            closeButton: false,
          ),
          isDarkBackground: false,
          isLoading: provider.isLoading,
          children: [
                SizedBox(height: HeightSpacing.medium),


                Text(
                  product!.value.valueRealWithCents,
                  style: AppTypography.infoItemSmall(context),
                ),
                SizedBox(height: 5),
                Text(
                  'Adicionado por ${product.addedBy ?? 'Desconhecido'}',
                  style: AppTypography.infoItemSmall(context),
                ),

                SizedBox(height: HeightSpacing.large),

                // Comentários
                Text('Comentários',
                  style: AppTypography.infoItemSmall(context),
                ),
                SizedBox(height: HeightSpacing.smallMedium),
                Expanded(
                  child: ListView.builder(
                    itemCount: product.comments?.length ?? 0,
                    itemBuilder: (context, index) {
                      final comment = product.comments![index];
                      return Container(
                        margin: EdgeInsets.only(bottom: HeightSpacing.small),
                        padding: EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: appColors.white,
                          borderRadius: BorderRadius.circular(10),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black12,
                              blurRadius: 4,
                              offset: Offset(0, 2),
                            )
                          ],
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              comment.userName ?? 'Anônimo',
                              style: AppTypography.bodyBold(context),
                            ),
                            SizedBox(height: 4),
                            Text(
                              comment.text ?? '',
                              style: AppTypography.infoItemSmall(context),
                            ),
                          ],
                        ),
                      );
                    },
                  ),
                ),

                // Campo para novo comentário
                SizedBox(height: HeightSpacing.small),
                CommentInputField(),
                SizedBox(height: HeightSpacing.medium),
              ],
        ),

    );
  }
}

class CommentInputField extends StatefulWidget {
  @override
  State<CommentInputField> createState() => _CommentInputFieldState();
}

class _CommentInputFieldState extends State<CommentInputField> {

  final TextEditingController _controller = TextEditingController();



  @override
  Widget build(BuildContext context) {
    final appColors = ThemeProvider.of(context).appColors;
    final provider = context.watch<MainProvider>();
    final product = provider.productDetail;
    void _sendComment() {
      final text = _controller.text.trim();
      if (text.isNotEmpty) {
        // Aqui você chamaria um método no provider para enviar o comentário
       // provider.commentProduct(product!.id, text);
        _controller.clear();
      }
    }
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: appColors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: appColors.grey600),
      ),
      child: Row(
        children: [
          Expanded(
            child: TextField(
              controller: _controller,
              decoration: InputDecoration.collapsed(
                hintText: 'Adicionar um comentário...',
              ),
            ),
          ),
          IconButton(
            onPressed: _sendComment,
            icon: Icon(Icons.send, color: appColors.primary),
          ),
        ],
      ),
    );
  }
}
