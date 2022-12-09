<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector;
use Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use Rector\CodeQuality\Rector\For_\ForToForeachRector;
use Rector\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector;
use Rector\CodeQuality\Rector\FuncCall\AddPregQuoteDelimiterRector;
use Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector;
use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\If_\ShortenElseIfRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodeQuality\Rector\Ternary\UnnecessaryTernaryExpressionRector;
use Rector\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector;
use Rector\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Rector\MethodCall\EntityAliasToClassConstantReferenceRector;
use Rector\Doctrine\Rector\Property\DoctrineTargetEntityStringToClassConstantRector;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector;
use Rector\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Rector\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector;
use Rector\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Rector\BinaryOp\ResponseStatusCodeRector;
use Rector\Symfony\Rector\Class_\CommandPropertyToAttributeRector;
use Rector\Symfony\Rector\Class_\MakeCommandLazyRector;
use Rector\Symfony\Rector\ClassConstFetch\ConsoleExceptionToErrorEventConstantRector;
use Rector\Symfony\Rector\ClassMethod\ActionSuffixRemoverRector;
use Rector\Symfony\Rector\ClassMethod\ConsoleExecuteReturnIntRector;
use Rector\Symfony\Rector\ClassMethod\FormTypeGetParentRector;
use Rector\Symfony\Rector\ClassMethod\GetRequestRector;
use Rector\Symfony\Rector\ClassMethod\MergeMethodAnnotationToRouteAnnotationRector;
use Rector\Symfony\Rector\ClassMethod\ReplaceSensioRouteAnnotationWithSymfonyRector;
use Rector\Symfony\Rector\ClassMethod\ResponseReturnTypeControllerActionRector;
use Rector\Symfony\Rector\ClassMethod\TemplateAnnotationToThisRenderRector;
use Rector\Symfony\Rector\MethodCall\AuthorizationCheckerIsGrantedExtractorRector;
use Rector\Symfony\Rector\MethodCall\ChangeCollectionTypeOptionNameFromTypeToEntryTypeRector;
use Rector\Symfony\Rector\MethodCall\ChangeStringCollectionOptionToConstantRector;
use Rector\Symfony\Rector\MethodCall\ContainerGetToConstructorInjectionRector;
use Rector\Symfony\Rector\MethodCall\FormTypeInstanceToClassConstRector;
use Rector\Symfony\Rector\MethodCall\GetHelperControllerToServiceRector;
use Rector\Symfony\Rector\MethodCall\LiteralGetToRequestClassConstantRector;
use Rector\Symfony\Rector\MethodCall\MakeDispatchFirstArgumentEventRector;
use Rector\Symfony\Rector\MethodCall\ReadOnlyOptionToAttributeRector;
use Rector\Symfony\Rector\New_\RootNodeTreeBuilderRector;
use Rector\Symfony\Rector\Return_\SimpleFunctionAndFilterRector;
use Rector\Symfony\Rector\StaticCall\BinaryFileResponseCreateToNewInstanceRector;
use Rector\Symfony\Set\SensiolabsSetList;
use Rector\Symfony\Set\SwiftmailerSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Set\TwigSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();
    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/index.php', __DIR__ . '/return.php',]);

    $rectorConfig->rule(SimplifyUselessVariableRector::class);
    $rectorConfig->rule(RemoveAlwaysElseRector::class);
    $rectorConfig->rule(CountArrayToEmptyArrayComparisonRector::class);
    $rectorConfig->rule(ForToForeachRector::class);
    $rectorConfig->rule(ChangeNestedForeachIfsToEarlyContinueRector::class);
    $rectorConfig->rule(ChangeIfElseValueAssignToEarlyReturnRector::class);
    $rectorConfig->rule(SimplifyStrposLowerRector::class);
    $rectorConfig->rule(CombineIfRector::class);
    $rectorConfig->rule(SimplifyIfReturnBoolRector::class);
    $rectorConfig->rule(InlineIfToExplicitIfRector::class);
    $rectorConfig->rule(PreparedValueToEarlyReturnRector::class);
    $rectorConfig->rule(ShortenElseIfRector::class);
    $rectorConfig->rule(SimplifyIfElseToTernaryRector::class);
    $rectorConfig->rule(UnusedForeachValueToArrayKeysRector::class);
    $rectorConfig->rule(ChangeArrayPushToArrayAssignRector::class);
    $rectorConfig->rule(UnnecessaryTernaryExpressionRector::class);
    $rectorConfig->rule(AddPregQuoteDelimiterRector::class);
    $rectorConfig->rule(SimplifyRegexPatternRector::class);
    $rectorConfig->rule(FuncGetArgsToVariadicParamRector::class);
    $rectorConfig->rule(MakeInheritedMethodVisibilitySameAsParentRector::class);
    $rectorConfig->rule(SimplifyEmptyArrayCheckRector::class);
    $rectorConfig->rule(NormalizeNamespaceByPSR4ComposerAutoloadRector::class);
    $rectorConfig->rule(CommandPropertyToAttributeRector::class);
    $rectorConfig->rule(DoctrineTargetEntityStringToClassConstantRector::class);

    $rectorConfig->rule(ActionSuffixRemoverRector::class);
    $rectorConfig->rule(AuthorizationCheckerIsGrantedExtractorRector::class);
    $rectorConfig->rule(BinaryFileResponseCreateToNewInstanceRector::class);
    $rectorConfig->rule(ChangeCollectionTypeOptionNameFromTypeToEntryTypeRector::class);
    $rectorConfig->rule(ChangeStringCollectionOptionToConstantRector::class);
    $rectorConfig->rule(ConsoleExceptionToErrorEventConstantRector::class);
    $rectorConfig->rule(ConsoleExecuteReturnIntRector::class);
    $rectorConfig->rule(ContainerGetToConstructorInjectionRector::class);
    $rectorConfig->rule(FormTypeInstanceToClassConstRector::class);
    $rectorConfig->rule(FormTypeGetParentRector::class);
    $rectorConfig->rule(GetHelperControllerToServiceRector::class);
    $rectorConfig->rule(GetRequestRector::class);
    $rectorConfig->rule(LiteralGetToRequestClassConstantRector::class);
    $rectorConfig->rule(MakeCommandLazyRector::class);
    $rectorConfig->rule(MakeDispatchFirstArgumentEventRector::class);
    $rectorConfig->rule(MergeMethodAnnotationToRouteAnnotationRector::class);
    $rectorConfig->rule(ReadOnlyOptionToAttributeRector::class);
    $rectorConfig->rule(ReplaceSensioRouteAnnotationWithSymfonyRector::class);
    $rectorConfig->rule(ResponseReturnTypeControllerActionRector::class);
    $rectorConfig->rule(ResponseStatusCodeRector::class);
    $rectorConfig->rule(RootNodeTreeBuilderRector::class);
    $rectorConfig->rule(SimpleFunctionAndFilterRector::class);
    $rectorConfig->rule(TemplateAnnotationToThisRenderRector::class);

    $rectorConfig->importNames();
    $rectorConfig->sets(
        [
            SymfonySetList::SYMFONY_CODE_QUALITY,
            SwiftmailerSetList::SWIFTMAILER_TO_SYMFONY_MAILER,
            TwigSetList::TWIG_240,
            SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
            SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
            SymfonySetList::SYMFONY_60,
            LevelSetList::UP_TO_PHP_82,
            DoctrineSetList::DOCTRINE_CODE_QUALITY,
            DoctrineSetList::DOCTRINE_DBAL_30,
            DoctrineSetList::DOCTRINE_ORM_29,
            DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
            SensiolabsSetList::FRAMEWORK_EXTRA_61,
        ]
    );
};
