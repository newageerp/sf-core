import React from "react";
import Select from "react-select";
import { useTranslation } from 'react-i18next';

interface Props {
  value?: string;
  onChange: (val: any) => void;
  options: any[];
  isMulti?: boolean;
  required?: boolean;
  className?: string,
  components?: any
}
export interface SelectFieldOption {
  value: string;
  label: string;
  extraData?: any,
}

export default function OldSelectFieldMulti(props: Props) {
  const { value, onChange, options } = props;

  const { t } = useTranslation();

  const selected = options.filter((t: SelectFieldOption) => value && value.indexOf(t.value) >= 0);

  const _val = selected.length > 0 ? selected : undefined;

  const className = [""];
  if (props.required) {
    if (!_val) {
      className.push("required-error");
    }
  }
  if (props.className) {
    className.push(props.className);
  }

  return (
    <Select
      placeholder={t("Choose...")}
      options={options}
      className={className.join(" ")}
      isMulti={props.isMulti}
      value={_val}
      onChange={(i: any) => onChange(i.map((v: any) => parseInt(v.value, 10)))}
      components={props.components}
    />
  );
}
