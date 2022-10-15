import React, { Fragment } from "react";
import { useTranslation } from "react-i18next";
import { FieldLabel } from '@newageerp/v3.form.field-label'
import { Tooltip } from '@newageerp/ui.texts.base.tooltip'

interface Props {
  title: string;
  className?: string;
  width?: string;
  isRequired?: boolean;
  tooltip?: any
}

export default function FormFieldLabel(props: Props) {
  const { t } = useTranslation();

  return <FieldLabel
    isRequired={props.isRequired}
    className={props.className}
    width={props.width}
    >
    <Fragment>
      {t(props.title)}
      {props.tooltip && <Tooltip text={props.tooltip} className={'tw3-ml-1'} />}
    </Fragment>
  </FieldLabel>;
}
