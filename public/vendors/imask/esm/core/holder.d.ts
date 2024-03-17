import type { default as _InputMask, InputMaskElement as _InputMaskElement } from '../../src/controls/input';
import type { default as _Masked } from '../../src/masked/base';
import type { default as _MaskedPattern } from '../../src/masked/pattern';
import type { default as _RepeatBlock } from '../../src/masked/repeat';
import type { default as _MaskedDate } from '../../src/masked/date';
import type { default as _MaskedDynamic } from '../../src/masked/dynamic';
import type { default as _MaskedEnum } from '../../src/masked/enum';
import type { default as _MaskedRange } from '../../src/masked/range';
import type { default as _MaskedNumber } from '../../src/masked/number';
import type { default as _MaskedFunction } from '../../src/masked/function';
import type { default as _MaskedRegExp } from '../../src/masked/regexp';
import type { default as _createMask, FactoryArg } from '../../src/masked/factory';
import type { default as _ChangeDetails } from '../../src/core/change-details';
import type { default as _MaskElement } from '../../src/controls/mask-element';
import type { default as _HTMLMaskElement } from '../../src/controls/html-mask-element';
import type { default as _HTMLContenteditableMaskElement } from '../../src/controls/html-contenteditable-mask-element';
import type { createPipe as _createPipe, pipe as _pipe, PIPE_TYPE as _PIPE_TYPE } from '../../src/masked/pipe';
/** Applies mask on element */
declare function IMask<Opts extends FactoryArg>(el: _InputMaskElement, opts: Opts): _InputMask<Opts>;
declare namespace IMask {
    let InputMask: typeof _InputMask;
    let createMask: typeof _createMask;
    let Masked: typeof _Masked;
    let MaskedPattern: typeof _MaskedPattern;
    let RepeatBlock: typeof _RepeatBlock;
    let MaskedDate: typeof _MaskedDate;
    let MaskedDynamic: typeof _MaskedDynamic;
    let MaskedEnum: typeof _MaskedEnum;
    let MaskedRange: typeof _MaskedRange;
    let MaskedNumber: typeof _MaskedNumber;
    let MaskedFunction: typeof _MaskedFunction;
    let MaskedRegExp: typeof _MaskedRegExp;
    let ChangeDetails: typeof _ChangeDetails;
    let MaskElement: typeof _MaskElement;
    let HTMLMaskElement: typeof _HTMLMaskElement;
    let HTMLContenteditableMaskElement: typeof _HTMLContenteditableMaskElement;
    let createPipe: typeof _createPipe;
    let pipe: typeof _pipe;
    let PIPE_TYPE: typeof _PIPE_TYPE;
}
export default IMask;
//# sourceMappingURL=holder.d.ts.map