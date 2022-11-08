import React, { Fragment, useEffect, useState } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { useTranslation } from 'react-i18next';
import { FieldTextarea } from '@newageerp/v3.bundles.form-bundle'
import LargeTextEditableField from './LargeTextEditableField';
import FormHint from '../FormHint';

interface Props {
  fieldKey: string;
}

export default function StringArrayEditableField(props: Props) {
  const { t } = useTranslation();

  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  const [localVal, setLocalVal] = useState(element ? element[props.fieldKey].join("\n") : '');

  const updateValue = (e: any) => setLocalVal(e.target.value);
  useEffect(() => {
    if (element && element[props.fieldKey] !== localVal) {
      setLocalVal(element[props.fieldKey].join("\n"));
    }
  }, [element]);


  const onBlur = () => {
    updateElement(props.fieldKey, localVal.split("\n"))
  };

  const handleKeyDown = (event: any) => {
    if (event.key === 'Enter' && event.shiftKey) {
      onBlur();
    }
  };

  return (
    <div>
      <FieldTextarea
        value={localVal}
        onChange={updateValue}
        className={'tw3-w-full tw3-block'}
        autoRows={true}
        rows={2}
        onBlur={onBlur}
        onKeyDown={handleKeyDown}
      />
      <FormHint text={t('Naują reikšmę veskite į naują eilutę')} />
    </div>
  )
}
