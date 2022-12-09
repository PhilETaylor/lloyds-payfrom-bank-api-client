<?php

declare(strict_types=1);
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\Basic\BracesFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\NoSuperfluousElseifFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\ReturnNotation\SimplifiedNullReturnFixer;
use PhpCsFixer\Fixer\Semicolon\SemicolonAfterInstructionFixer;
use Symplify\CodingStandard\Fixer\Annotation\RemovePHPStormAnnotationFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\Commenting\ParamReturnAndVarTagMalformsFixer;
use Symplify\CodingStandard\Fixer\Commenting\RemoveUselessDefaultCommentFixer;
use Symplify\CodingStandard\Fixer\LineLength\DocBlockLineLengthFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLinePromotedPropertyFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([
        SetList::PSR_12,
        SetList::DOCTRINE_ANNOTATIONS,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::CLEAN_CODE,
        SetList::ARRAY,
        SetList::COMMENTS,
        //        SetList::COMMON,
        //        SetList::CONTROL_STRUCTURES,
        SetList::PHPUNIT,
        SetList::SPACES,
        //        SetList::STRICT,
    ]);

    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);

    $ecsConfig->ruleWithConfiguration(LineLengthFixer::class, [
        LineLengthFixer::LINE_LENGTH => 140,
    ]);

    $ecsConfig->ruleWithConfiguration(BinaryOperatorSpacesFixer::class, [
        'default' => 'align',
    ]);

    $ecsConfig->ruleWithConfiguration(HeaderCommentFixer::class, [
        'header' => <<<EOF
@copyright  Copyright (C) 2022 Blue Flame Digital Solutions Limited / Phil Taylor. All rights reserved.
@copyright  Copyright (C) 2022 Red Evolution Limited. All rights reserved.
@author     Phil Taylor <phil@phil-taylor.com>
@see        https://github.com/PhilETaylor/lloyds-payfrom-bank-api-client
@license    The GNU General Public License v3.0
EOF,
        'comment_type' => 'comment',
        'separate'     => 'bottom',
    ]);

    $ecsConfig->paths([ 'src', 'index.php', 'return.php']);
    $ecsConfig->parallel();

    $services = $ecsConfig->services();

    $services->set(NativeConstantInvocationFixer::class);
    $services->set(NativeFunctionInvocationFixer::class);
    $services->set(StandaloneLinePromotedPropertyFixer::class);
    $services->set(StandaloneLineInMultilineArrayFixer::class);
    $services->set(RemoveUselessDefaultCommentFixer::class);
    $services->set(RemovePHPStormAnnotationFixer::class);
    $services->set(ParamReturnAndVarTagMalformsFixer::class);
    $services->set(DocBlockLineLengthFixer::class);
    $services->set(SimplifiedNullReturnFixer::class);
    $services->set(BracesFixer::class);
    $services->set(ProtectedToPrivateFixer::class);
    $services->set(SemicolonAfterInstructionFixer::class);
    $services->set(BlankLineAfterOpeningTagFixer::class);
    $services->set(NoSuperfluousPhpdocTagsFixer::class);
    $services->set(NoSuperfluousElseifFixer::class);
};
