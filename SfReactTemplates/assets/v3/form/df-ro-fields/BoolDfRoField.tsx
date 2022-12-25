import { StatusWidget } from '@newageerp/v3.bundles.widgets-bundle';
import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { useTranslation } from 'react-i18next';

interface Props {
  fieldKey: string;
  id: number;
}

export default function BoolDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });
  const { t } = useTranslation();
  
  return (
    <StatusWidget color={value ? "teal" : "slate"}>{value ? t("Yes") : t("No")}</StatusWidget>
  )
}
