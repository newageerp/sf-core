import React from "react";
import { Template, TemplatesParser } from "../templates/TemplateLoader";
import { TabContainer as TabContainerImpl } from "@newageerp/ui.tabs.tab-container";

type Props = {
  items: {
    title: string;
    content: Template[];
  }[];
  title?: string;
};

export default function TabContainer(props: Props) {
  return (
    <TabContainerImpl
      title={props.title ? { text: props.title } : undefined}
      items={props.items.map((t) => {
        return {
          content: <TemplatesParser templates={t.content} />,
          tab: {
            children: t.title,
          },
        };
      })}
    />
  );
}
